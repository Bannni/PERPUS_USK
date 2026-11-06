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
            'selected_carts' => ['nullable', 'array'],
            'selected_carts.*' => ['integer'],
        ], [
            'shipping_address.required' => 'Alamat pengiriman lengkap wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman minimal 10 karakter.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        $userId = Auth::id();
        $query = Cart::with(['book.stock'])->where('user_id', $userId);

        if ($request->has('selected_carts')) {
            $selectedIds = $request->input('selected_carts');
            if (empty($selectedIds)) {
                return redirect()->route('cart.index')->with('error', 'Pilih minimal satu buku di keranjang untuk diproses ke checkout!');
            }
            $query->whereIn('id', $selectedIds);
        }

        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada buku yang dipilih atau keranjang belanja Anda masih kosong!');
        }

        DB::beginTransaction();

        try {
            // Validate stock availability with lockForUpdate before placing order
            foreach ($cartItems as $item) {
                $stock = Stock::where('book_id', $item->book_id)->lockForUpdate()->first();
                $avail = $stock ? $stock->quantity : 0;
                if ($item->quantity > $avail) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', "Stok untuk buku '{$item->book->title}' tidak mencukupi (Tersisa: {$avail})!");
                }
            }

            $totalPrice = $cartItems->sum(function ($item) {
                return $item->book->price * $item->quantity;
            });

            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Create Order header (Initial status: pending, stock_deducted: false)
            $order = Order::create([
                'order_code' => $orderCode,
                'order_number' => $orderCode,
                'user_id' => $userId,
                'book_id' => $cartItems->first()->book_id, // primary book reference
                'quantity' => $cartItems->sum('quantity'),
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending', // Initial status as required by BNSP PRD
                'stock_deducted' => false, // Stock only decrements when order is ACC'd by Admin
                'shipping_address' => $validated['shipping_address'],
                'buyer_notes' => $validated['buyer_notes'] ?? null,
            ]);

            // Create order_items (stock will be decremented upon Admin ACC)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->book->price,
                ]);
            }

            // Empty only the selected cart items from user's cart
            Cart::where('user_id', $userId)->whereIn('id', $cartItems->pluck('id'))->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', "Pesanan #{$order->order_code} berhasil dibuat! Menunggu persetujuan (ACC) dari Admin.");
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
            'payment_method' => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'min:10', 'max:500'],
            'buyer_notes' => ['nullable', 'string', 'max:255'],
        ], [
            'shipping_address.required' => 'Alamat pengiriman lengkap wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman minimal 10 karakter.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        $book = Book::with('stock')->findOrFail($validated['book_id']);
        $quantity = $validated['quantity'] ?? 1;

        DB::beginTransaction();

        try {
            $stock = Stock::where('book_id', $book->id)->lockForUpdate()->first();
            $currentStock = $stock ? $stock->quantity : 0;
            if ($quantity > $currentStock) {
                DB::rollBack();
                return back()->with('error', "Stok buku '{$book->title}' tidak mencukupi (Tersisa: {$currentStock})!");
            }

            $totalPrice = $book->price * $quantity;
            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $order = Order::create([
                'order_code' => $orderCode,
                'order_number' => $orderCode,
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'stock_deducted' => false,
                'shipping_address' => $validated['shipping_address'],
                'buyer_notes' => $validated['buyer_notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'quantity' => $quantity,
                'price' => $book->price,
            ]);

            DB::commit();

            return redirect()->route('orders.index')->with('success', "Pembelian buku '{$book->title}' berhasil diajukan! Pesanan #{$order->order_code} menunggu persetujuan (ACC) Admin.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}
