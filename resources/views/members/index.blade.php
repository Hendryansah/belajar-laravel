@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')
    <h2>Daftar Anggota Perpustakaan</h2>

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

    <p><a href="{{ route('members.create') }}">+ Tambah Anggota Baru</a></p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Peminjaman</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td align="center"><code>{{ $member->code }}</code></td>
                    <td><strong>{{ $member->name }}</strong></td>
                    <td>{{ $member->email ?? '-' }}</td>
                    <td>{{ $member->phone ?? '-' }}</td>
                    <td align="center">{{ $member->borrowings_count }} transaksi</td>
                    <td align="center">
                        <a href="{{ route('members.show', $member) }}">Detail</a> |
                        <a href="{{ route('members.edit', $member) }}">Edit</a> |
                        <form action="{{ route('members.destroy', $member) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center">Belum ada data anggota.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
