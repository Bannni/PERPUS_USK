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
        $search = $request->input('q');
        $activeTab = $request->input('tab', 'shelves'); // 'shelves' or 'all'

        if (!Auth::check()) {
            $genresWithBooks = collect();
            $myBooks = collect();
            $continueReadingBook = null;
        } else {
            $purchasedBookIds = Order::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->pluck('book_id')
                ->unique();

            $query = Book::whereIn('id', $purchasedBookIds);

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
        $search = $request->input('q');
        $selectedCategory = $request->input('category');

        $query = Book::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('synopsis', 'like', "%{$search}%");
            });
        }

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $books = $query->latest()->paginate(12)->withQueryString();
        $categories = Book::select('category')->distinct()->pluck('category');

        // Check which books the logged-in user already purchased
        $userPurchasedIds = Auth::check()
            ? Order::where('user_id', Auth::id())->where('status', 'completed')->pluck('book_id')->toArray()
            : [];

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
