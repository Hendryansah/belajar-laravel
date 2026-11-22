@extends('layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>

    <p><a href="{{ route('borrowings.index') }}">&larr; Kembali ke Daftar Peminjaman</a></p>

    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="color: red; margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: #fafafa; border: 1px solid #ddd; padding: 15px; max-width: 600px; margin-bottom: 20px;">
        <table cellpadding="6" cellspacing="0">
            <tr>
                <td><strong>Kode Transaksi</strong></td>
                <td>: <code>{{ $borrowing->code }}</code></td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>: 
                    @if($borrowing->isReturned())
                        <span style="color: green; font-weight: bold;">Sudah Dikembalikan</span>
                    @else
                        <span style="color: orange; font-weight: bold;">Sedang Dipinjam</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Peminjam (Anggota)</strong></td>
                <td>: {{ $borrowing->member->name }} (<code>{{ $borrowing->member->code }}</code>)</td>
            </tr>
            <tr>
                <td><strong>Petugas Pencatat</strong></td>
                <td>: {{ $borrowing->officer->name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Pinjam</strong></td>
                <td>: {{ $borrowing->borrow_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Batas Pengembalian</strong></td>
                <td>: {{ $borrowing->due_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Pengembalian</strong></td>
                <td>: {{ $borrowing->return_date ? $borrowing->return_date->format('d F Y') : '-' }}</td>
            </tr>
        </table>

        @if(!$borrowing->isReturned())
            <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin buku telah dikembalikan?')">
                    @csrf
                    <button type="submit" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        Proses Pengembalian Buku
                    </button>
                </form>
            </div>
        @endif
    </div>

    <h3>Rincian Buku yang Dipinjam</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; max-width: 600px; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Jumlah Dipinjam</th>
                <th>Stok Tersedia Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowing->books as $index => $book)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $book->title }}</strong></td>
                    <td>{{ $book->author }}</td>
                    <td align="center">{{ $book->pivot->qty }} eksemplar</td>
                    <td align="center">{{ $book->stock }} eksemplar</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
