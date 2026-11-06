<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $book;
    protected $stock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_stock@readowl.com',
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'email' => 'user_stock@readowl.com',
        ]);

        $category = Category::create([
            'name' => 'Fiksi',
            'slug' => 'fiksi',
        ]);

        $this->book = Book::create([
            'category_id' => $category->id,
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'category' => 'Fiksi',
            'price' => 85000,
            'rating' => 4.9,
            'synopsis' => 'Kisah persahabatan anak-anak Belitong.',
            'cover_url' => 'https://example.com/cover.jpg',
        ]);

        $this->stock = Stock::where('book_id', $this->book->id)->first();
        if (!$this->stock) {
            $this->stock = Stock::create(['book_id' => $this->book->id, 'quantity' => 5]);
        } else {
            $this->stock->update(['quantity' => 5]);
        }
    }

    /**
     * 1. Stock does NOT decrement at checkout (only status pending & stock_deducted false).
     */
    public function test_stock_does_not_decrement_on_checkout_until_approved()
    {
        Cart::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)->post('/checkout', [
            'shipping_address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
            'payment_method' => 'Payment at Delivery (COD)',
        ]);

        $response->assertRedirect(route('orders.index'));

        // Assert order created with status pending and stock_deducted false
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertFalse($order->stock_deducted);

        // Assert stock is STILL 5 (NOT decremented yet!)
        $this->assertEquals(5, $this->stock->fresh()->quantity);
    }

    /**
     * 2. Stock decrements when Admin ACCs / approves the order.
     */
    public function test_stock_decrements_when_admin_approves_order()
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-001',
            'order_number' => 'ORD-TEST-001',
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'quantity' => 2,
            'total_price' => 170000,
            'payment_method' => 'Payment at Delivery (COD)',
            'status' => 'pending',
            'stock_deducted' => false,
            'shipping_address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $this->book->id,
            'quantity' => 2,
            'price' => 85000,
        ]);

        // Stock before ACC is 5
        $this->assertEquals(5, $this->stock->fresh()->quantity);

        // Admin updates status to 'processing' (ACC)
        $response = $this->actingAs($this->admin)->put(route('admin.orders.updateStatus', $order->id), [
            'status' => 'processing',
        ]);

        $response->assertRedirect(route('admin.dashboard', ['tab' => 'orders']));
        $response->assertSessionHas('success');

        // Stock should NOW be 5 - 2 = 3
        $this->assertEquals(3, $this->stock->fresh()->quantity);
        $this->assertTrue($order->fresh()->stock_deducted);
        $this->assertEquals('processing', $order->fresh()->status);
    }

    /**
     * 3. Stock is restored when Admin cancels an approved order.
     */
    public function test_stock_is_restored_when_order_is_cancelled()
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-002',
            'order_number' => 'ORD-TEST-002',
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'quantity' => 2,
            'total_price' => 170000,
            'payment_method' => 'Payment at Delivery (COD)',
            'status' => 'processing',
            'stock_deducted' => true,
            'shipping_address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $this->book->id,
            'quantity' => 2,
            'price' => 85000,
        ]);

        // Simulate stock was reduced to 3
        $this->stock->update(['quantity' => 3]);

        // Admin cancels the order
        $response = $this->actingAs($this->admin)->put(route('admin.orders.updateStatus', $order->id), [
            'status' => 'cancelled',
        ]);

        $response->assertRedirect(route('admin.dashboard', ['tab' => 'orders']));

        // Stock should be restored to 3 + 2 = 5
        $this->assertEquals(5, $this->stock->fresh()->quantity);
        $this->assertFalse($order->fresh()->stock_deducted);
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    /**
     * 4. Admin cannot ACC an order if stock has become insufficient.
     */
    public function test_admin_cannot_acc_order_if_stock_insufficient()
    {
        $order = Order::create([
            'order_code' => 'ORD-TEST-003',
            'order_number' => 'ORD-TEST-003',
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'quantity' => 10,
            'total_price' => 850000,
            'payment_method' => 'Payment at Delivery (COD)',
            'status' => 'pending',
            'stock_deducted' => false,
            'shipping_address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $this->book->id,
            'quantity' => 10,
            'price' => 85000,
        ]);

        // Available stock is only 5, order needs 10
        $this->assertEquals(5, $this->stock->fresh()->quantity);

        $response = $this->actingAs($this->admin)->put(route('admin.orders.updateStatus', $order->id), [
            'status' => 'processing',
        ]);

        $response->assertSessionHas('error');
        // Stock should NOT be minus, still 5
        $this->assertEquals(5, $this->stock->fresh()->quantity);
        $this->assertFalse($order->fresh()->stock_deducted);
    }

    /**
     * 5. User cannot exceed stock quantity in cart.
     */
    public function test_cart_quantity_cannot_exceed_available_stock()
    {
        // Available stock is 5
        // Try adding 6
        $response = $this->actingAs($this->user)->post('/cart', [
            'book_id' => $this->book->id,
            'quantity' => 6,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, Cart::where('user_id', $this->user->id)->count());

        // Add 5 (allowed)
        $response = $this->actingAs($this->user)->post('/cart', [
            'book_id' => $this->book->id,
            'quantity' => 5,
        ]);
        $response->assertSessionHas('success');

        $cart = Cart::where('user_id', $this->user->id)->first();
        $this->assertEquals(5, $cart->quantity);

        // Try updating cart quantity to 6 (exceeds stock)
        $response = $this->actingAs($this->user)->patch("/cart/{$cart->id}", [
            'quantity' => 6,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(5, $cart->fresh()->quantity);
    }
}
