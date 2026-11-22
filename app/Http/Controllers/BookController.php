<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Book::class);

        $books = Book::with('category')->get();

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Book::class);

        $title = 'Tambah Buku';
        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Book::class);

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255|unique:books,title',
            'author' => 'required|string|min:3|max:255',
            'year' => 'required|integer|digits:4|min:1900|max:'.date('Y'),
            'stock' => 'required|integer|min:0|max:10000',
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.min' => 'Judul buku minimal harus 3 karakter.',
            'title.max' => 'Judul buku maksimal 255 karakter.',
            'title.unique' => 'Buku dengan judul ini sudah terdaftar.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.min' => 'Nama penulis minimal harus 3 karakter.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.min' => 'Tahun minimal adalah 1900.',
            'year.max' => 'Tahun tidak boleh melebihi tahun sekarang ('.date('Y').').',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh bernilai negatif.',
            'stock.max' => 'Stok maksimal adalah 10.000.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak tersedia.',
        ]);

        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        Gate::authorize('view', $book);

        $book->load('category');
        $title = 'Detail Buku';

        return view('books.show', compact('title', 'book'));
    }

    public function edit(Book $book)
    {
        Gate::authorize('update', $book);

        $title = 'Edit Buku';
        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('title', 'book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        Gate::authorize('update', $book);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255', Rule::unique('books', 'title')->ignore($book->id)],
            'author' => ['required', 'string', 'min:3', 'max:255'],
            'year' => ['required', 'integer', 'digits:4', 'min:1900', 'max:'.date('Y')],
            'stock' => ['required', 'integer', 'min:0', 'max:10000'],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.min' => 'Judul buku minimal harus 3 karakter.',
            'title.max' => 'Judul buku maksimal 255 karakter.',
            'title.unique' => 'Buku dengan judul ini sudah terdaftar.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.min' => 'Nama penulis minimal harus 3 karakter.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
            'year.min' => 'Tahun minimal adalah 1900.',
            'year.max' => 'Tahun tidak boleh melebihi tahun sekarang ('.date('Y').').',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh bernilai negatif.',
            'stock.max' => 'Stok maksimal adalah 10.000.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak tersedia.',
        ]);

        $book->update($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        Gate::authorize('delete', $book);

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
