<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders / purchases.
     */
    public function index()
    {
        $orders = Order::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Store a newly created order / purchase in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'buyer_notes' => ['nullable', 'string', 'max:255'],
        ]);

        $book = Book::findOrFail($validated['book_id']);
        $quantity = $validated['quantity'] ?? 1;
        $totalPrice = $book->price * $quantity;

        $order = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'payment_method' => $validated['payment_method'] ?? 'Transfer Bank / QRIS',
            'status' => 'completed',
            'buyer_notes' => $validated['buyer_notes'] ?? null,
        ]);

        return redirect()->back()->with('success', "Pembelian buku '{$book->title}' berhasil! Transaksi #{$order->order_number} telah tercatat.");
    }
}
