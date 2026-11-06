<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShelfPhysicalBookTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_shelf@readowl.com',
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'email' => 'user_shelf@readowl.com',
        ]);

        $category = Category::create([
            'name' => 'Sains',
            'slug' => 'sains',
        ]);

        $this->book = Book::create([
            'category_id' => $category->id,
            'title' => 'Cosmos',
            'author' => 'Carl Sagan',
            'publisher' => 'Gramedia',
            'category' => 'Sains',
            'price' => 125000,
            'rating' => 5.0,
            'synopsis' => 'Menjelajahi alam semesta.',
            'cover_url' => 'https://example.com/cosmos.jpg',
        ]);
    }

    /**
     * Test that guest (unauthenticated) CANNOT access shelves and is redirected to login.
     */
    public function test_guest_is_redirected_to_login_when_accessing_shelves()
    {
        $response = $this->get('/catalog');
        $response->assertRedirect(route('login'));

        $responseLibrary = $this->get('/library');
        $responseLibrary->assertRedirect(route('login'));
    }

    /**
     * Test that after logout, accessing shelves redirects to login and sends no-cache headers.
     */
    public function test_user_cannot_access_shelves_after_logout()
    {
        // 1. User is logged in and can view shelves
        $this->actingAs($this->user)->get('/catalog')->assertStatus(200);

        // 2. User logs out
        $this->post('/logout')->assertRedirect(route('home'));

        // 3. User tries to view shelves again -> MUST REDIRECT TO LOGIN
        $response = $this->get('/catalog');
        $response->assertRedirect(route('login'));

        // 4. Assert headers prevent back-history caching
        $response->assertHeader('Cache-Control');
    }

    /**
     * Test that pending orders DO NOT appear in My Library / Shelves.
     */
    public function test_pending_orders_do_not_appear_on_shelves()
    {
        // User has a pending order
        $order = Order::create([
            'order_code' => 'ORD-PENDING-001',
            'order_number' => 'ORD-PENDING-001',
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'quantity' => 1,
            'total_price' => 125000,
            'payment_method' => 'Payment at Delivery (COD)',
            'status' => 'pending',
            'stock_deducted' => false,
            'shipping_address' => 'Jl. Anggrek No. 12, Bandung',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $this->book->id,
            'quantity' => 1,
            'price' => 125000,
        ]);

        // Visit /catalog as the user
        $response = $this->actingAs($this->user)->get('/catalog');
        $response->assertStatus(200);

        // Should see empty state, NOT the Cosmos book
        $response->assertSee('Rak Koleksi Buku Masih Kosong');
        $response->assertDontSee('Bab I — Permulaan Kisah'); // No digital reader
    }

    /**
     * Test that when order is updated to completed/processing (ACC / Success), it appears on the shelf.
     */
    public function test_approved_or_completed_orders_appear_on_shelves()
    {
        $order = Order::create([
            'order_code' => 'ORD-CONFIRMED-001',
            'order_number' => 'ORD-CONFIRMED-001',
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'quantity' => 1,
            'total_price' => 125000,
            'payment_method' => 'Payment at Delivery (COD)',
            'status' => 'completed', // Confirmed / Selesai
            'stock_deducted' => true,
            'shipping_address' => 'Jl. Anggrek No. 12, Bandung',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $this->book->id,
            'quantity' => 1,
            'price' => 125000,
        ]);

        $response = $this->actingAs($this->user)->get('/catalog');
        $response->assertStatus(200);

        // Book must now appear on the shelf
        $response->assertSee('Cosmos');
        $response->assertSee('Carl Sagan');
        $response->assertSee('Sains');

        // Digital e-reader must NOT be present
        $response->assertDontSee('Buka Pembaca Digital');
        $response->assertDontSee('readerOpen');
        $response->assertDontSee('startReading');
        $response->assertSee('Buku Fisik Resmi Dimiliki');
    }

    /**
     * Test that cancelled orders DO NOT appear on the shelf.
     */
    public function test_cancelled_orders_do_not_appear_on_shelves()
    {
        $order = Order::create([
            'order_code' => 'ORD-CANCELLED-001',
            'order_number' => 'ORD-CANCELLED-001',
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'quantity' => 1,
            'total_price' => 125000,
            'payment_method' => 'Payment at Delivery (COD)',
            'status' => 'cancelled',
            'stock_deducted' => false,
            'shipping_address' => 'Jl. Anggrek No. 12, Bandung',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $this->book->id,
            'quantity' => 1,
            'price' => 125000,
        ]);

        $response = $this->actingAs($this->user)->get('/catalog');
        $response->assertStatus(200);

        $response->assertSee('Rak Koleksi Buku Masih Kosong');
        $response->assertDontSee('Cosmos');
    }
}
