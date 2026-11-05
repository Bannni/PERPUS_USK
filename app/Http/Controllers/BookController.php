<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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
     * Display the bookstore catalog / shelves page.
     * Matches the Readowl bookshelf layout.
     */
    public function catalog(Request $request)
    {
        $search = $request->input('q');
        $activeTab = $request->input('tab', 'shelves'); // 'shelves' or 'all'

        $query = Book::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $allBooks = (clone $query)->orderBy('title')->get();

        $currentlyReading = (clone $query)->where('shelf_section', 'currently_reading')->get();
        $nextUp = (clone $query)->where('shelf_section', 'next_up')->get();
        $finished = (clone $query)->where('shelf_section', 'finished')->get();

        // Continue reading book for bottom-left widget
        $continueReadingBook = Book::where('shelf_section', 'currently_reading')
            ->where('reading_progress', '>', 0)
            ->first() ?? Book::first();

        $categories = Book::select('category')->distinct()->pluck('category');

        return view('catalog', compact(
            'allBooks',
            'currentlyReading',
            'nextUp',
            'finished',
            'continueReadingBook',
            'search',
            'activeTab',
            'categories'
        ));
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
