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
     * Display admin dashboard and book CRUD management.
     */
    public function index(Request $request)
    {
        $search = $request->input('q');
        $category = $request->input('category');

        $query = Book::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $books = $query->latest()->paginate(9)->withQueryString();
        $categories = Book::select('category')->distinct()->pluck('category');

        // Statistics
        $totalBooks = Book::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalUsers = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'books',
            'categories',
            'totalBooks',
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'search',
            'category'
        ));
    }

    /**
     * Display user data monitoring for admin.
     */
    public function users(?Request $request = null)
    {
        $request = $request ?? request();
        $search = $request->input('q');

        $query = User::withCount(['orders' => function ($q) {
            $q->where('status', 'completed');
        }])->withSum(['orders' => function ($q) {
            $q->where('status', 'completed');
        }], 'total_price');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $totalUsers = User::count();
        $totalMembers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.users', compact('users', 'totalUsers', 'totalMembers', 'totalAdmins', 'search'));
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
     * View all transaction orders.
     */
    public function orders()
    {
        $orders = Order::with(['user', 'book'])->latest()->paginate(15);
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('admin.orders', compact('orders', 'totalRevenue'));
    }
}
