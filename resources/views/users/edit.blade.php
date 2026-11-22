@extends('layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>

    <p><a href="{{ route('users.index') }}">&larr; Kembali ke Pengelolaan Pengguna</a></p>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="name">Nama Lengkap</label><br>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" style="width: 320px; padding: 5px;" required>
            @error('name')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" style="width: 320px; padding: 5px;" required>
            @error('email')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label><br>
            <input type="password" name="password" id="password" placeholder="Password baru" style="width: 320px; padding: 5px;">
            @error('password')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="role">Role Pengguna</label><br>
            <select name="role" id="role" style="width: 332px; padding: 5px;" required>
                <option value="petugas" @selected(old('role', $user->role) == 'petugas')>Petugas</option>
                <option value="admin" @selected(old('role', $user->role) == 'admin')>Admin</option>
            </select>
            @error('role')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" style="padding: 6px 15px;">Perbarui Pengguna</button>
    </form>
@endsection
