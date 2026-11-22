@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h2>Katalog Buku Perpustakaan</h2>

    @if (session('success'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="color: red; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
    @endif
    
    <p><a href="{{ route('books.create') }}">+ Tambah Buku Baru</a></p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>Judul Buku</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $index => $book)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $book->title }}</strong></td>
                    <td>{{ $book->category?->name ?? 'Tanpa Kategori' }}</td>
                    <td>{{ $book->author }}</td>
                    <td align="center">{{ $book->year }}</td>
                    <td align="center">
                        <span style="font-weight: bold; color: {{ $book->stock > 0 ? 'green' : 'red' }};">
                            {{ $book->stock }}
                        </span>
                    </td>
                    <td align="center">
                        <a href="{{ route('books.show', $book) }}">Detail</a> |
                        <a href="{{ route('books.edit', $book) }}">Edit</a>
                        @can('delete', $book)
                            |
                            <form action="{{ route('books.destroy', $book) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">Hapus</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center">Belum ada buku dalam katalog.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
