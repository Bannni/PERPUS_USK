<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        return redirect()->route('admin.dashboard', ['tab' => 'categories']);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah terdaftar.',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'categories'])->with('success', "Kategori '{$category->name}' berhasil ditambahkan!");
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah terdaftar.',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'categories'])->with('success', "Kategori '{$category->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = Category::withCount('books')->findOrFail($id);
        $name = $category->name;

        if ($category->books_count > 0) {
            return redirect()->route('admin.dashboard', ['tab' => 'categories'])->with('error', "Kategori '{$name}' tidak dapat dihapus karena masih menaungi {$category->books_count} buku!");
        }

        $category->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'categories'])->with('success', "Kategori '{$name}' berhasil dihapus.");
    }
}
