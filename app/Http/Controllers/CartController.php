<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display user shopping cart.
     */
    public function index()
    {
        $carts = Cart::with(['book.stock', 'book.categoryRelation'])
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $carts->sum(function ($item) {
            return ($item->book->price ?? 0) * $item->quantity;
        });

        return view('cart.index', compact('carts', 'subtotal'));
    }

    /**
     * Add a book to shopping cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $book = Book::with('stock')->findOrFail($validated['book_id']);
        $quantityToAdd = $validated['quantity'] ?? 1;

        // Check stock availability
        $currentStock = $book->stock ? $book->stock->quantity : 0;
        if ($currentStock < 1) {
            return back()->with('error', "Maaf, stok buku '{$book->title}' sedang habis!");
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantityToAdd;
            if ($newQuantity > $currentStock) {
                return back()->with('error', "Jumlah pesanan melebihi sisa stok yang tersedia ({$currentStock} buah)!");
            }
            $cart->update(['quantity' => $newQuantity]);
        } else {
            if ($quantityToAdd > $currentStock) {
                return back()->with('error', "Jumlah pesanan melebihi sisa stok yang tersedia ({$currentStock} buah)!");
            }
            Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => $quantityToAdd,
            ]);
        }

        return redirect()->route('cart.index')->with('success', "Buku '{$book->title}' berhasil ditambahkan ke keranjang belanja!");
    }

    /**
     * Update item quantity in shopping cart.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $book = $cart->book;
        $currentStock = $book->stock ? $book->stock->quantity : 0;

        if ($validated['quantity'] > $currentStock) {
            return back()->with('error', "Kuantitas melebihi stok yang tersedia ({$currentStock} buah)!");
        }

        $cart->update(['quantity' => $validated['quantity']]);

        return redirect()->route('cart.index')->with('success', 'Kuantitas keranjang berhasil diperbarui.');
    }

    /**
     * Remove an item from shopping cart.
     */
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $title = $cart->book->title ?? 'Buku';
        $cart->delete();

        return redirect()->route('cart.index')->with('success', "Buku '{$title}' telah dihapus dari keranjang belanja.");
    }
}
