<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    /**
     * Display admin dashboard and book management.
     */
    public function index(Request $request)
    {
        $search = $request->input('q');
        $shelf = $request->input('shelf');

        $query = Book::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($shelf && in_array($shelf, ['currently_reading', 'next_up', 'finished'])) {
            $query->where('shelf_section', $shelf);
        }

        $books = $query->latest()->paginate(9)->withQueryString();

        // Statistics
        $totalBooks = Book::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalUsers = User::where('role', 'user')->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'book'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'books',
            'totalBooks',
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'recentOrders',
            'search',
            'shelf'
        ));
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'shelf_section' => ['required', 'in:currently_reading,next_up,finished'],
            'price' => ['required', 'integer', 'min:0'],
            'cover_url' => ['required', 'url', 'max:500'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'reading_progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'published_year' => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'synopsis' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'category.required' => 'Kategori wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'cover_url.required' => 'URL sampul buku wajib diisi.',
            'cover_url.url' => 'Format URL sampul tidak valid.',
        ]);

        $validated['rating'] = $validated['rating'] ?? 4.5;
        $validated['pages'] = $validated['pages'] ?? 250;
        $validated['reading_progress'] = $validated['reading_progress'] ?? 0;

        Book::create($validated);

        return redirect()->route('admin.dashboard')->with('success', "Buku '{$validated['title']}' berhasil ditambahkan ke kurasi perpustakaan!");
    }

    /**
     * Update the specified book in database.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'shelf_section' => ['required', 'in:currently_reading,next_up,finished'],
            'price' => ['required', 'integer', 'min:0'],
            'cover_url' => ['required', 'url', 'max:500'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'reading_progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'published_year' => ['nullable', 'integer'],
            'synopsis' => ['nullable', 'string'],
        ]);

        $book->update($validated);

        return redirect()->route('admin.dashboard')->with('success', "Perubahan buku '{$book->title}' berhasil disimpan!");
    }

    /**
     * Remove the specified book from database.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $title = $book->title;
        $book->delete();

        return redirect()->route('admin.dashboard')->with('success', "Buku '{$title}' telah berhasil dihapus dari sistem.");
    }

    /**
     * View all transaction orders.
     */
    public function orders()
    {
        $orders = Order::with(['user', 'book'])->latest()->paginate(15);
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('admin.orders', compact('orders', 'totalRevenue'));
    }
}
