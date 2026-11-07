<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminBookController extends Controller
{
    /**
     * Display a unified admin dashboard (Book CRUD, User Monitoring, Order Logs, Category CRUD, Stock, Contacts).
     */
    public function index(Request $request)
    {
        $activeTab = $request->input('tab', 'books');
        $search = $request->input('q');
        $category = $request->input('category');
        $stockFilter = $request->input('stock_filter');

        // 1. Books query with stocks and categories
        $bookQuery = Book::with(['stock', 'categoryRelation']);
        if ($search && $activeTab === 'books') {
            $bookQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        if ($category) {
            $bookQuery->where(function ($q) use ($category) {
                $q->where('category', $category)
                  ->orWhereHas('categoryRelation', function ($sub) use ($category) {
                      $sub->where('name', $category)->orWhere('slug', $category);
                  });
            });
        }

        // Low stock / Out of stock filter
        if ($stockFilter === 'low') {
            $bookQuery->whereHas('stock', function ($q) {
                $q->where('quantity', '>', 0)->where('quantity', '<=', 3);
            });
        } elseif ($stockFilter === 'out') {
            $bookQuery->where(function ($q) {
                $q->whereDoesntHave('stock')
                  ->orWhereHas('stock', function ($sub) {
                      $sub->where('quantity', '<=', 0);
                  });
            });
        } elseif ($stockFilter === 'safe') {
            $bookQuery->whereHas('stock', function ($q) {
                $q->where('quantity', '>', 3);
            });
        }

        $books = $bookQuery->latest()->paginate(9, ['*'], 'books_page')->withQueryString();
        $categories = Category::withCount('books')->get();

        // Stock stats
        $lowStockCount = Stock::where('quantity', '>', 0)->where('quantity', '<=', 3)->count();
        $outOfStockCount = Stock::where('quantity', '<=', 0)->count() + Book::doesntHave('stock')->count();

        // 2. Users query
        $userQuery = User::withCount(['orders'])
            ->withSum('orders', 'total_price');

        if ($search && $activeTab === 'users') {
            $userQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        $users = $userQuery->latest()->paginate(10, ['*'], 'users_page')->withQueryString();

        // 3. Orders query
        $orders = Order::with(['user', 'book', 'items.book'])->latest()->paginate(15, ['*'], 'orders_page')->withQueryString();

        // 4. Contacts query (BNSP Requirement)
        $contacts = Contact::with('user')->latest()->paginate(10, ['*'], 'contacts_page')->withQueryString();
        $unreadContacts = Contact::where('is_read', false)->count();

        // Summary Statistics
        $totalBooks = Book::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['completed', 'processing'])->sum('total_price');
        $totalUsers = User::count();
        $totalMembers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalStockQty = Stock::sum('quantity');

        return view('admin.dashboard', compact(
            'activeTab',
            'books',
            'categories',
            'users',
            'orders',
            'contacts',
            'unreadContacts',
            'totalBooks',
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'totalMembers',
            'totalAdmins',
            'totalStockQty',
            'search',
            'category',
            'stockFilter',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    /**
     * Mark a contact message as read / toggle status.
     */
    public function markContactAsRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]);

        return redirect()->route('admin.dashboard', ['tab' => 'contacts'])->with('success', 'Pesan telah ditandai sebagai sudah dibaca.');
    }

    /**
     * Delete contact message.
     */
    public function destroyContact($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'contacts'])->with('success', 'Pesan kontak berhasil dihapus.');
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
            'title' => ['required', 'string', 'max:255', 'unique:books,title'],
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
            'title.unique' => 'Judul buku sudah terdaftar dalam katalog! Tidak boleh menambahkan buku dengan judul yang sama.',
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
            'title' => ['required', 'string', 'max:255', Rule::unique('books', 'title')->ignore($book->id)],
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
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.unique' => 'Judul buku sudah terdaftar dalam katalog! Tidak boleh menggunakan judul yang sama dengan buku lain.',
            'author.required' => 'Nama penulis wajib diisi.',
            'category.required' => 'Kategori / Genre wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
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
