<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $title = 'Tambah Kategori';

        return view('categories.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.min' => 'Nama kategori minimal 2 karakter.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Kategori ini sudah terdaftar.',
        ]);

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $title = 'Edit Kategori';

        return view('categories.edit', compact('title', 'category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.min' => 'Nama kategori minimal 2 karakter.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Kategori ini sudah terdaftar.',
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh buku.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
