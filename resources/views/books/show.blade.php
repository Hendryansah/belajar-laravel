@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <h2>Detail Buku</h2>
    <p>Judul: {{ $book->title }}</p>
    <p>Kategori: {{ $book->category?->name ?? 'Kategori tidak tersedia' }}</p>
    <p>Penulis: {{ $book->author }}</p>
    <p>Tahun: {{ $book->year }}</p>
    <p>Stok: {{ $book->stock }}</p>
    <a href="{{ route('books.index') }}">Kembali ke Daftar Buku</a>
@endsection