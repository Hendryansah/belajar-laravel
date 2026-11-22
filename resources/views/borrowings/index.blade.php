@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
    <h2>Transaksi Peminjaman Buku</h2>

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

    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <a href="{{ route('borrowings.create') }}" style="font-weight: bold;">+ Catat Peminjaman Baru</a>
        </div>
        <div>
            Filter Status:
            <a href="{{ route('borrowings.index') }}" style="{{ empty($status) ? 'font-weight: bold; text-decoration: underline;' : '' }}">Semua</a> |
            <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" style="{{ $status === 'borrowed' ? 'font-weight: bold; text-decoration: underline;' : '' }}">Sedang Dipinjam</a> |
            <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" style="{{ $status === 'returned' ? 'font-weight: bold; text-decoration: underline;' : '' }}">Sudah Dikembalikan</a>
        </div>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>Kode</th>
                <th>Anggota Peminjam</th>
                <th>Petugas</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $index => $borrowing)
                <tr style="{{ $borrowing->isOverdue() ? 'background-color: #fff2f2;' : '' }}">
                    <td align="center">{{ $index + 1 }}</td>
                    <td align="center"><code>{{ $borrowing->code }}</code></td>
                    <td><strong>{{ $borrowing->member->name }}</strong> ({{ $borrowing->member->code }})</td>
                    <td>{{ $borrowing->officer->name ?? '-' }}</td>
                    <td align="center">{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                    <td align="center">
                        {{ $borrowing->due_date->format('d/m/Y') }}
                        @if($borrowing->isOverdue())
                            <br><small style="color: red; font-weight: bold;">(Terlambat!)</small>
                        @endif
                    </td>
                    <td align="center">{{ $borrowing->return_date ? $borrowing->return_date->format('d/m/Y') : '-' }}</td>
                    <td align="center">
                        @if($borrowing->status === 'returned')
                            <span style="color: green; font-weight: bold;">Dikembalikan</span>
                        @else
                            <span style="color: orange; font-weight: bold;">Dipinjam</span>
                        @endif
                    </td>
                    <td align="center">
                        <a href="{{ route('borrowings.show', $borrowing) }}">Detail</a>
                        @if(!$borrowing->isReturned())
                            |
                            <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                                @csrf
                                <button type="submit" style="color: blue; background: none; border: none; cursor: pointer; text-decoration: underline;">Kembalikan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" align="center">Belum ada data peminjaman buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
