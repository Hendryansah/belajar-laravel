@extends('layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>

    <p><a href="{{ route('members.index') }}">&larr; Kembali ke Daftar Anggota</a></p>

    <div style="background: #fafafa; padding: 15px; border: 1px solid #ddd; max-width: 500px; margin-bottom: 20px;">
        <p><strong>Kode Anggota:</strong> <code>{{ $member->code }}</code></p>
        <p><strong>Nama:</strong> {{ $member->name }}</p>
        <p><strong>Email:</strong> {{ $member->email ?? '-' }}</p>
        <p><strong>Telepon:</strong> {{ $member->phone ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $member->address ?? '-' }}</p>
        <p><strong>Terdaftar Sejak:</strong> {{ $member->created_at->format('d M Y') }}</p>
    </div>

    <h3>Riwayat Transaksi Peminjaman</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Kode Transaksi</th>
                <th>Tanggal Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tanggal Pengembalian</th>
                <th>Buku yang Dipinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($member->borrowings as $borrowing)
                <tr>
                    <td align="center"><a href="{{ route('borrowings.show', $borrowing) }}"><code>{{ $borrowing->code }}</code></a></td>
                    <td align="center">{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                    <td align="center">{{ $borrowing->due_date->format('d/m/Y') }}</td>
                    <td align="center">{{ $borrowing->return_date ? $borrowing->return_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        <ul>
                            @foreach($borrowing->books as $b)
                                <li>{{ $b->title }} ({{ $b->pivot->qty }} eks)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td align="center">
                        @if($borrowing->status === 'returned')
                            <span style="color: green; font-weight: bold;">Dikembalikan</span>
                        @else
                            <span style="color: orange; font-weight: bold;">Sedang Dipinjam</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center">Belum ada riwayat peminjaman untuk anggota ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
