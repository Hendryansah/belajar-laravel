<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        // $books = [
        //     ['id' => 1, 'title' => 'Pemrograman PHP', 'author' => 'Andi', 'year' => 2021],
        //     ['id' => 2, 'title' => 'Laravel untuk Pemula', 'author' => 'Budi', 'year' => 2022],
        //     ['id' => 3, 'title' => 'Basis Data', 'author' => 'Citra', 'year' => 2020],
        //     ['id' => 4, 'title' => 'Algoritma dan Pemrograman', 'author' => 'Dewi', 'year' => 2023],
        //     ['id' => 5, 'title' => 'Pemrograman Berorientasi Objek', 'author' => 'Eko', 'year' => 2019],
        //     ['id' => 6, 'title' => 'Struktur Data', 'author' => 'Fajar', 'year' => 2021],
        //     ['id' => 7, 'title' => 'Jaringan Komputer', 'author' => 'Gilang', 'year' => 2020],
        //     ['id' => 8, 'title' => 'Sistem Operasi', 'author' => 'Hendra', 'year' => 2022],
        //     ['id' => 9, 'title' => 'Sistem hukum', 'author' => 'dadang', 'year' => 2022],
        //     ['id' => 10, 'title' => 'sekilas info', 'author' => 'jason ranti', 'year' => 2023],
        // ];

        $books = Book::all();
        
        return view('books.index', compact('books'));
    }

    public function show($id)
    {
        return view('books.show', compact('id'));
    }
}
