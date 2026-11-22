@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Library System Dashboard</h2>
    <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong> (Role: <em>{{ ucfirst(Auth::user()->role) }}</em>)!</p>

    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 25px;">
        <div style="border: 1px solid #ccc; background: #f8f9fa; padding: 15px; border-radius: 6px; min-width: 150px; text-align: center;">
            <div style="font-size: 26px; font-weight: bold; color: #007bff;">{{ $bookCount }}</div>
            <div style="color: #666; font-size: 14px;">Judul Buku</div>
        </div>

        <div style="border: 1px solid #ccc; background: #f8f9fa; padding: 15px; border-radius: 6px; min-width: 150px; text-align: center;">
            <div style="font-size: 26px; font-weight: bold; color: #28a745;">{{ $totalStock }}</div>
            <div style="color: #666; font-size: 14px;">Total Stok Buku</div>
        </div>

        <div style="border: 1px solid #ccc; background: #f8f9fa; padding: 15px; border-radius: 6px; min-width: 150px; text-align: center;">
            <div style="font-size: 26px; font-weight: bold; color: #17a2b8;">{{ $categoryCount }}</div>
            <div style="color: #666; font-size: 14px;">Kategori Buku</div>
        </div>

        <div style="border: 1px solid #ccc; background: #f8f9fa; padding: 15px; border-radius: 6px; min-width: 150px; text-align: center;">
            <div style="font-size: 26px; font-weight: bold; color: #6f42c1;">{{ $memberCount }}</div>
            <div style="color: #666; font-size: 14px;">Jumlah Anggota</div>
        </div>

        <div style="border: 1px solid #ccc; background: #f8f9fa; padding: 15px; border-radius: 6px; min-width: 150px; text-align: center;">
            <div style="font-size: 26px; font-weight: bold; color: #fd7e14;">{{ $borrowedCount }}</div>
            <div style="color: #666; font-size: 14px;">Sedang Dipinjam</div>
        </div>

        <div style="border: 1px solid #ccc; background: #f8f9fa; padding: 15px; border-radius: 6px; min-width: 150px; text-align: center;">
            <div style="font-size: 26px; font-weight: bold; color: #20c997;">{{ $returnedCount }}</div>
            <div style="color: #666; font-size: 14px;">Selesai Kembali</div>
        </div>
    </div>

    <h3>5 Transaksi Peminjaman Terkini</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBorrowings as $b)
                <tr>
                    <td align="center"><code>{{ $b->code }}</code></td>
                    <td>{{ $b->member->name }}</td>
                    <td>
                        {{ $b->books->pluck('title')->implode(', ') }}
                    </td>
                    <td align="center">{{ $b->borrow_date->format('d/m/Y') }}</td>
                    <td align="center">{{ $b->due_date->format('d/m/Y') }}</td>
                    <td align="center">
                        @if($b->isReturned())
                            <span style="color: green; font-weight: bold;">Dikembalikan</span>
                        @else
                            <span style="color: orange; font-weight: bold;">Dipinjam</span>
                        @endif
                    </td>
                    <td align="center"><a href="{{ route('borrowings.show', $b) }}">Detail</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center">Belum ada transaksi peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
