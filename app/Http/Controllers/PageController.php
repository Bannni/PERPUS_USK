<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the About Us / Profil Toko page (BNSP Requirement).
     */
    public function about()
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();

        return view('pages.about', compact('totalBooks', 'totalCategories'));
    }
}
