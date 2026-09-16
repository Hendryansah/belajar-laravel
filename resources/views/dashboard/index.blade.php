@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Library System Dashboard</h2>
    <p>Selamat datang di Sistem Informasi Perpustakaan.</p>

    <ul>
        <li>Jumlah Buku: {{ $bookCount }}</li>
        <li>Jumlah Member: {{ $memberCount }}</li>
        <li>Jumlah Kategori: {{ $categoryCount }}</li>
    </ul>
@endsection
