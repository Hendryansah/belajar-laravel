@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <h2>Pengelolaan Pengguna Sistem (Admin Only)</h2>

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

    <p><a href="{{ route('users.create') }}">+ Tambah Pengguna Baru</a></p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tgl Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $user->name }}</strong> @if(auth()->id() === $user->id) <em>(Anda)</em> @endif</td>
                    <td>{{ $user->email }}</td>
                    <td align="center">
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-weight: bold; background: {{ $user->role === 'admin' ? '#e2e3e5' : '#d4edda' }}; color: {{ $user->role === 'admin' ? '#383d41' : '#155724' }};">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td align="center">{{ $user->created_at?->format('d/m/Y') ?? '-' }}</td>
                    <td align="center">
                        <a href="{{ route('users.edit', $user) }}">Edit</a>
                        @if(auth()->id() !== $user->id)
                            |
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center">Belum ada pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
