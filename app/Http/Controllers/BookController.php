<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display the bookstore landing page.
     */
    public function landing()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak! Administrator diarahkan langsung ke Panel Admin Studio.');
        }

        $featuredBooks = Book::take(6)->get();
        $totalBooks = Book::count();
        $categories = Book::select('category')->distinct()->pluck('category');

        return view('landing', compact('featuredBooks', 'totalBooks', 'categories'));
    }

    /**
     * Display the user's personal library (Shelves).
     * Only displays books that have been purchased by the logged-in user.
     * Shelves are separated by Book Genre / Category.
     */
    public function myLibrary(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak! Administrator tidak diizinkan mengakses halaman My Library pembaca.');
        }

        $search = $request->input('q');
        $activeTab = $request->input('tab', 'shelves'); // 'shelves' or 'all'

        if (!Auth::check()) {
            $genresWithBooks = collect();
            $myBooks = collect();
            $continueReadingBook = null;
        } else {
            $userId = Auth::id();
            $fromOrders = Order::where('user_id', $userId)
                ->whereIn('status', ['completed', 'processing', 'pending'])
                ->pluck('book_id');

            $fromItems = \App\Models\OrderItem::whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('status', ['completed', 'processing', 'pending']);
            })->pluck('book_id');

            $purchasedBookIds = $fromOrders->merge($fromItems)->unique();

            $query = Book::with('stock')->whereIn('id', $purchasedBookIds);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
                });
            }

            $myBooks = $query->get();

            // Group by genre / category (as requested!)
            $genresWithBooks = $myBooks->groupBy('category');

            // Latest book for continue reading widget
            $continueReadingBook = $myBooks->first();
        }

        return view('catalog', compact(
            'genresWithBooks',
            'myBooks',
            'continueReadingBook',
            'search',
            'activeTab'
        ));
    }

    /**
     * Display the bookstore Shop / Storefront.
     * All books available to purchase with genre filters and checkout.
     */
    public function shop(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak! Administrator tidak diizinkan mengakses Toko / Belanja Buku.');
        }

        $search = $request->input('q');
        $selectedCategory = $request->input('category');

        $query = Book::with('stock');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%")
                  ->orWhere('synopsis', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $books = $query->latest()->paginate(12)->withQueryString();
        $categories = Book::select('category')->distinct()->pluck('category');

        // Check which books the logged-in user already purchased
        if (Auth::check()) {
            $uId = Auth::id();
            $oIds = Order::where('user_id', $uId)->whereIn('status', ['completed', 'processing'])->pluck('book_id');
            $iIds = \App\Models\OrderItem::whereHas('order', function ($q) use ($uId) {
                $q->where('user_id', $uId)->whereIn('status', ['completed', 'processing']);
            })->pluck('book_id');
            $userPurchasedIds = $oIds->merge($iIds)->unique()->toArray();
        } else {
            $userPurchasedIds = [];
        }

        return view('shop', compact('books', 'categories', 'selectedCategory', 'search', 'userPurchasedIds'));
    }

    /**
     * Get single book details (API / JSON).
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        return response()->json($book);
    }
}
