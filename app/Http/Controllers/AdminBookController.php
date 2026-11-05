<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    /**
     * Display a unified admin dashboard (Book CRUD, User Monitoring, Order Logs).
     */
    public function index(Request $request)
    {
        $activeTab = $request->input('tab', 'books');
        $search = $request->input('q');
        $category = $request->input('category');

        // 1. Books query
        $bookQuery = Book::query();
        if ($search && $activeTab === 'books') {
            $bookQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        if ($category) {
            $bookQuery->where('category', $category);
        }
        $books = $bookQuery->latest()->paginate(9, ['*'], 'books_page')->withQueryString();
        $categories = Book::select('category')->distinct()->pluck('category');

        // 2. Users query
        $userQuery = User::withCount(['orders' => function ($q) {
            $q->where('status', 'completed');
        }])->withSum(['orders' => function ($q) {
            $q->where('status', 'completed');
        }], 'total_price');

        if ($search && $activeTab === 'users') {
            $userQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        $users = $userQuery->latest()->paginate(10, ['*'], 'users_page')->withQueryString();

        // 3. Orders query
        $orders = Order::with(['user', 'book'])->latest()->paginate(15, ['*'], 'orders_page')->withQueryString();

        // Summary Statistics
        $totalBooks = Book::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalUsers = User::count();
        $totalMembers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.dashboard', compact(
            'activeTab',
            'books',
            'categories',
            'users',
            'orders',
            'totalBooks',
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'totalMembers',
            'totalAdmins',
            'search',
            'category'
        ));
    }

    /**
     * Redirect to the dashboard user tab.
     */
    public function users()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'users']);
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
            'shelf_section' => ['nullable', 'in:currently_reading,next_up,finished'],
            'price' => ['required', 'integer', 'min:0'],
            'cover_url' => ['nullable', 'string', 'max:500'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'published_year' => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'synopsis' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'category.required' => 'Kategori / Genre wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
        ]);

        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('covers', 'public');
            $validated['cover_url'] = '/storage/' . $path;
        } elseif (empty($validated['cover_url'])) {
            $validated['cover_url'] = 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';
        }

        $validated['shelf_section'] = $validated['shelf_section'] ?? 'next_up';
        $validated['rating'] = $validated['rating'] ?? 4.8;
        $validated['pages'] = $validated['pages'] ?? 250;

        Book::create($validated);

        return redirect()->route('admin.dashboard')->with('success', "Buku '{$validated['title']}' berhasil ditambahkan ke database toko!");
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
            'shelf_section' => ['nullable', 'in:currently_reading,next_up,finished'],
            'price' => ['required', 'integer', 'min:0'],
            'cover_url' => ['nullable', 'string', 'max:500'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'published_year' => ['nullable', 'integer'],
            'synopsis' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('covers', 'public');
            $validated['cover_url'] = '/storage/' . $path;
        } elseif (empty($validated['cover_url'])) {
            $validated['cover_url'] = $book->cover_url;
        }

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

        if ($book->cover_url && str_starts_with($book->cover_url, '/storage/covers/')) {
            $relPath = str_replace('/storage/', '', $book->cover_url);
            Storage::disk('public')->delete($relPath);
        }

        $book->delete();

        return redirect()->route('admin.dashboard')->with('success', "Buku '{$title}' telah berhasil dihapus dari sistem.");
    }

    /**
     * Redirect to the dashboard orders tab.
     */
    public function orders()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'orders']);
    }
}
