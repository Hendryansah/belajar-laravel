@extends('layouts.app')

@section('title', 'Kategori Buku')

@section('content')
    <h2>Daftar Kategori Buku</h2>

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

    <p><a href="{{ route('categories.create') }}">+ Tambah Kategori Baru</a></p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="width: 50px;">No</th>
                <th>Nama Kategori</th>
                <th style="width: 120px;">Jumlah Buku</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $index => $category)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td align="center">{{ $category->books_count }} buku</td>
                    <td align="center">
                        <a href="{{ route('categories.edit', $category) }}">Edit</a> |
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" align="center">Belum ada kategori buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
