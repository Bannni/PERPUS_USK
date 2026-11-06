<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders / purchases.
     */
    public function index()
    {
        $orders = Order::with(['book', 'items.book'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Handle Cart Checkout (BNSP Requirement: Section 4.1).
     * Payment at Delivery (COD), stock reduction, and order_items creation.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'min:10', 'max:500'],
            'payment_method' => ['required', 'string', 'max:100'],
            'buyer_notes' => ['nullable', 'string', 'max:255'],
        ], [
            'shipping_address.required' => 'Alamat pengiriman lengkap wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman minimal 10 karakter.',
            'payment_method.required' => 'Pilih metode pembayaran (Payment at Delivery).',
        ]);

        $userId = Auth::id();
        $cartItems = Cart::with(['book.stock'])->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong!');
        }

        // Validate stock availability for all items before placing order
        foreach ($cartItems as $item) {
            $stock = $item->book->stock ? $item->book->stock->quantity : 0;
            if ($item->quantity > $stock) {
                return redirect()->route('cart.index')->with('error', "Stok untuk buku '{$item->book->title}' tidak mencukupi (Tersisa: {$stock})!");
            }
        }

        DB::beginTransaction();

        try {
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->book->price * $item->quantity;
            });

            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Create Order header
            $order = Order::create([
                'order_code' => $orderCode,
                'order_number' => $orderCode,
                'user_id' => $userId,
                'book_id' => $cartItems->first()->book_id, // primary book reference
                'quantity' => $cartItems->sum('quantity'),
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending', // Initial status as required by BNSP PRD
                'shipping_address' => $validated['shipping_address'],
                'buyer_notes' => $validated['buyer_notes'] ?? null,
            ]);

            // Create order_items and decrement stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->book->price,
                ]);

                // Reduce stock in stocks table
                if ($item->book->stock) {
                    $item->book->stock->decrement('quantity', $item->quantity);
                }
            }

            // Empty the user's cart
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', "Pesanan #{$order->order_code} berhasil dibuat dengan metode {$order->payment_method}! Kuantitas stok otomatis diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage());
        }
    }

    /**
     * Direct 1-Click Purchase (Quick Buy from Storefront).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'shipping_address' => ['nullable', 'string', 'max:500'],
            'buyer_notes' => ['nullable', 'string', 'max:255'],
        ]);

        $book = Book::with('stock')->findOrFail($validated['book_id']);
        $quantity = $validated['quantity'] ?? 1;

        $currentStock = $book->stock ? $book->stock->quantity : 0;
        if ($quantity > $currentStock) {
            return back()->with('error', "Stok buku '{$book->title}' tidak mencukupi (Tersisa: {$currentStock})!");
        }

        $totalPrice = $book->price * $quantity;
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_code' => $orderCode,
                'order_number' => $orderCode,
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'] ?? 'Payment at Delivery (COD)',
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'] ?? 'Pengiriman standar ke alamat terdaftar',
                'buyer_notes' => $validated['buyer_notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'quantity' => $quantity,
                'price' => $book->price,
            ]);

            if ($book->stock) {
                $book->stock->decrement('quantity', $quantity);
            }

            DB::commit();

            return redirect()->route('orders.index')->with('success', "Pembelian buku '{$book->title}' berhasil! Pesanan #{$order->order_code} tercatat dan stok berkurang.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}
