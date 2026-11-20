<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'books']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:books,title'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'cover_url' => ['required_without:cover_file', 'nullable', 'string', 'max:500'],
            'cover_file' => ['required_without:cover_url', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'published_year' => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'description' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.unique' => 'Judul buku sudah terdaftar dalam katalog! Tidak boleh menambahkan buku dengan judul yang sama.',
            'author.required' => 'Nama penulis wajib diisi.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'price.required' => 'Harga buku wajib diisi.',
            'stock_quantity.required' => 'Jumlah stok buku wajib diisi.',
            'cover_url.required_without' => 'Gambar sampul buku wajib ditambahkan (unggah file gambar atau masukkan URL gambar).',
            'cover_file.required_without' => 'Gambar sampul buku wajib ditambahkan (unggah file gambar atau masukkan URL gambar).',
            'cover_file.image' => 'Berkas sampul harus berupa gambar yang valid (JPEG, PNG, JPG, WebP).',
            'cover_file.mimes' => 'Format gambar yang diperbolehkan: JPEG, PNG, JPG, WebP.',
            'cover_file.max' => 'Ukuran berkas gambar maksimal 4MB.',
        ]);

        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('covers', 'public');
            $validated['cover_image'] = '/storage/' . $path;
            $validated['cover_url'] = '/storage/' . $path;
        } elseif (!empty($validated['cover_url'])) {
            $validated['cover_image'] = $validated['cover_url'];
        }

        $category = Category::find($validated['category_id']);
        $validated['category'] = $category ? $category->name : 'Umum';
        $validated['synopsis'] = $validated['description'] ?? null;
        $validated['rating'] = $validated['rating'] ?? 4.8;
        $validated['pages'] = $validated['pages'] ?? 250;
        $validated['shelf_section'] = 'next_up';

        $stockQuantity = $validated['stock_quantity'];
        unset($validated['stock_quantity']);

        $book = Book::create($validated);

        // Update or create in stocks table (BNSP Requirement)
        Stock::updateOrCreate(
            ['book_id' => $book->id],
            ['quantity' => $stockQuantity]
        );

        return redirect()->route('admin.dashboard', ['tab' => 'books'])->with('success', "Buku '{$book->title}' berhasil ditambahkan ke inventaris dengan stok {$stockQuantity} buah!");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('books', 'title')->ignore($book->id)],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'cover_url' => ['nullable', 'string', 'max:500'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'published_year' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.unique' => 'Judul buku sudah terdaftar dalam katalog! Tidak boleh menggunakan judul yang sama dengan buku lain.',
            'author.required' => 'Nama penulis wajib diisi.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'price.required' => 'Harga buku wajib diisi.',
            'stock_quantity.required' => 'Jumlah stok buku wajib diisi.',
        ]);

        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('covers', 'public');
            $validated['cover_image'] = '/storage/' . $path;
            $validated['cover_url'] = '/storage/' . $path;
        } elseif (!empty($validated['cover_url'])) {
            $validated['cover_image'] = $validated['cover_url'];
        }

        $category = Category::find($validated['category_id']);
        $validated['category'] = $category ? $category->name : $book->category;
        $validated['synopsis'] = $validated['description'] ?? $book->synopsis;

        $stockQuantity = $validated['stock_quantity'];
        unset($validated['stock_quantity']);

        $book->update($validated);

        // Update stock quantity
        Stock::updateOrCreate(
            ['book_id' => $book->id],
            ['quantity' => $stockQuantity]
        );

        return redirect()->route('admin.dashboard', ['tab' => 'books'])->with('success', "Perubahan buku '{$book->title}' berhasil disimpan!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $title = $book->title;

        if ($book->cover_image && str_starts_with($book->cover_image, '/storage/covers/')) {
            $relPath = str_replace('/storage/', '', $book->cover_image);
            Storage::disk('public')->delete($relPath);
        }

        $book->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'books'])->with('success', "Buku '{$title}' dan data stoknya telah berhasil dihapus dari sistem.");
    }
}
