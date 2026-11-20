<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartSelectionAndDirectBuyTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $bookA;
    protected $bookB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'user',
            'email' => 'buyer@readowl.com',
        ]);

        $category = Category::create([
            'name' => 'Literatur',
            'slug' => 'literatur',
        ]);

        $this->bookA = Book::create([
            'category_id' => $category->id,
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'publisher' => 'Hasta Mitra',
            'category' => 'Literatur',
            'price' => 95000,
            'rating' => 5.0,
            'synopsis' => 'Kisah Minke di era kolonial.',
            'cover_url' => 'https://example.com/bumi.jpg',
        ]);
        $this->bookA->stock()->update(['quantity' => 10]);

        $this->bookB = Book::create([
            'category_id' => $category->id,
            'title' => 'Anak Semua Bangsa',
            'author' => 'Pramoedya Ananta Toer',
            'publisher' => 'Hasta Mitra',
            'category' => 'Literatur',
            'price' => 105000,
            'rating' => 4.9,
            'synopsis' => 'Kelanjutan kisah Minke.',
            'cover_url' => 'https://example.com/anak.jpg',
        ]);
        $this->bookB->stock()->update(['quantity' => 10]);
    }

    /**
     * 1. Cart view displays checkboxes, select all, and matched payment options.
     */
    public function test_cart_page_renders_selection_checkboxes_and_matching_payment_methods()
    {
        $cartA = Cart::create(['user_id' => $this->user->id, 'book_id' => $this->bookA->id, 'quantity' => 1]);
        $cartB = Cart::create(['user_id' => $this->user->id, 'book_id' => $this->bookB->id, 'quantity' => 2]);

        $response = $this->actingAs($this->user)->get('/cart');

        $response->assertStatus(200);
        $response->assertSee('Pilih Semua');
        $response->assertSee('name="selected_carts[]"', false);
        $response->assertSee('QRIS (Instant)');
        $response->assertSee('Transfer Bank BCA/Mandiri');
        $response->assertSee('E-Wallet GoPay/OVO');
        $response->assertDontSee('Payment at Delivery (COD)');
    }

    /**
     * 2. Checking out only selected cart items deletes only chosen items from cart.
     */
    public function test_checkout_processes_only_selected_cart_items_and_leaves_unselected_in_cart()
    {
        $cartA = Cart::create(['user_id' => $this->user->id, 'book_id' => $this->bookA->id, 'quantity' => 1]);
        $cartB = Cart::create(['user_id' => $this->user->id, 'book_id' => $this->bookB->id, 'quantity' => 2]);

        // Select only Book A
        $response = $this->actingAs($this->user)->post('/checkout', [
            'shipping_address' => 'Jl. Salemba Raya No. 10, Jakarta Pusat, DKI Jakarta',
            'payment_method' => 'QRIS (Instant)',
            'selected_carts' => [$cartA->id],
        ]);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success');

        // Check created order
        $order = Order::with('items')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(95000, $order->total_price);
        $this->assertEquals(1, $order->quantity);
        $this->assertEquals('QRIS (Instant)', $order->payment_method);
        $this->assertEquals('Jl. Salemba Raya No. 10, Jakarta Pusat, DKI Jakarta', $order->shipping_address);

        // Assert Book A was removed from cart, but Book B is STILL in cart!
        $this->assertDatabaseMissing('carts', ['id' => $cartA->id]);
        $this->assertDatabaseHas('carts', ['id' => $cartB->id, 'quantity' => 2]);
    }

    /**
     * 3. Checkout with empty selected_carts triggers validation error.
     */
    public function test_checkout_with_empty_selection_fails()
    {
        Cart::create(['user_id' => $this->user->id, 'book_id' => $this->bookA->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->post('/checkout', [
            'shipping_address' => 'Jl. Diponegoro No. 12, Bandung, Jawa Barat',
            'payment_method' => 'Transfer Bank BCA/Mandiri',
            'selected_carts' => [],
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    /**
     * 4. Direct Buy (orders.store) requires shipping address.
     */
    public function test_direct_buy_requires_shipping_address()
    {
        $response = $this->actingAs($this->user)->post(route('orders.store'), [
            'book_id' => $this->bookA->id,
            'quantity' => 1,
            'payment_method' => 'E-Wallet GoPay/OVO',
            'shipping_address' => '', // missing
        ]);

        $response->assertSessionHasErrors(['shipping_address']);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 5. Direct Buy (orders.store) succeeds with address and chosen payment method.
     */
    public function test_direct_buy_succeeds_with_shipping_address_and_payment_method()
    {
        $response = $this->actingAs($this->user)->post(route('orders.store'), [
            'book_id' => $this->bookA->id,
            'quantity' => 2,
            'payment_method' => 'Transfer Bank BCA/Mandiri',
            'shipping_address' => 'Gedung Wisma Antara Lt. 5, Jl. Medan Merdeka Selatan No. 17',
            'buyer_notes' => 'Tolong bungkus rapi dengan bubble wrap',
        ]);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success');

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals($this->bookA->id, $order->book_id);
        $this->assertEquals(2, $order->quantity);
        $this->assertEquals(190000, $order->total_price);
        $this->assertEquals('Transfer Bank BCA/Mandiri', $order->payment_method);
        $this->assertEquals('Gedung Wisma Antara Lt. 5, Jl. Medan Merdeka Selatan No. 17', $order->shipping_address);
        $this->assertEquals('Tolong bungkus rapi dengan bubble wrap', $order->buyer_notes);
        $this->assertEquals('pending', $order->status);
    }

    /**
     * 6. Direct buy modal in shop.blade.php contains shipping address and payment options.
     */
    public function test_shop_page_modal_contains_shipping_address_field()
    {
        $response = $this->actingAs($this->user)->get('/shop');

        $response->assertStatus(200);
        $response->assertSee('name="shipping_address"', false);
        $response->assertSee('Alamat Pengiriman Lengkap');
        $response->assertSee('QRIS (Instant)');
        $response->assertSee('Transfer Bank BCA/Mandiri');
        $response->assertSee('E-Wallet GoPay/OVO');
    }

    /**
     * 7. Direct buy rejects quantity exceeding available stock.
     */
    public function test_direct_buy_rejects_quantity_exceeding_available_stock()
    {
        $this->bookA->stock()->update(['quantity' => 3]);

        // Trying to buy 5 when stock is only 3
        $response = $this->actingAs($this->user)->post(route('orders.store'), [
            'book_id' => $this->bookA->id,
            'quantity' => 5,
            'payment_method' => 'QRIS (Instant)',
            'shipping_address' => 'Jl. Salemba Raya No. 10, Jakarta Pusat, DKI Jakarta',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, Order::count());

        // Buying 3 (exact available stock) succeeds
        $responseSuccess = $this->actingAs($this->user)->post(route('orders.store'), [
            'book_id' => $this->bookA->id,
            'quantity' => 3,
            'payment_method' => 'QRIS (Instant)',
            'shipping_address' => 'Jl. Salemba Raya No. 10, Jakarta Pusat, DKI Jakarta',
        ]);

        $responseSuccess->assertRedirect(route('orders.index'));
        $this->assertEquals(1, Order::count());
    }

    /**
     * 8. Admin cannot create book with duplicate title.
     */
    public function test_admin_cannot_create_book_with_duplicate_title()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::first();

        $response = $this->actingAs($admin)->post(route('admin.books.store'), [
            'title' => 'Bumi Manusia', // duplicate with bookA
            'author' => 'Pramoedya',
            'category_id' => $category->id,
            'price' => 90000,
            'stock_quantity' => 10,
            'cover_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    /**
     * 9. Admin must provide a cover image (file or URL) when adding a new book.
     */
    public function test_admin_must_provide_cover_image_on_create()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::first();

        // Submitting with NO image fails
        $responseFail = $this->actingAs($admin)->post(route('admin.books.store'), [
            'title' => 'Buku Baru Tanpa Sampul',
            'author' => 'Penulis',
            'category_id' => $category->id,
            'price' => 50000,
            'stock_quantity' => 5,
        ]);
        $responseFail->assertSessionHasErrors(['cover_url', 'cover_file']);

        // Submitting WITH cover_url succeeds
        $responseSuccess = $this->actingAs($admin)->post(route('admin.books.store'), [
            'title' => 'Buku Baru Dengan Sampul',
            'author' => 'Penulis',
            'category_id' => $category->id,
            'price' => 50000,
            'stock_quantity' => 5,
            'cover_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f',
        ]);
        $responseSuccess->assertRedirect(route('admin.dashboard', ['tab' => 'books']));
        $this->assertDatabaseHas('books', ['title' => 'Buku Baru Dengan Sampul']);
    }

    /**
     * 10. Admin can update book keeping its own title, but cannot use another existing book's title.
     */
    public function test_admin_title_unique_rule_on_update()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::first();

        // 1. Updating bookA keeping same title -> SUCCESS
        $response1 = $this->actingAs($admin)->put(route('admin.books.update', $this->bookA->id), [
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer Updated',
            'category_id' => $category->id,
            'price' => 95000,
            'stock_quantity' => 15,
        ]);
        $response1->assertSessionHasNoErrors();

        // 2. Updating bookB to have bookA's title -> FAIL DUPLICATE
        $response2 = $this->actingAs($admin)->put(route('admin.books.update', $this->bookB->id), [
            'title' => 'Bumi Manusia',
            'author' => 'Author',
            'category_id' => $category->id,
            'price' => 100000,
            'stock_quantity' => 5,
        ]);
        $response2->assertSessionHasErrors(['title']);
    }
}
