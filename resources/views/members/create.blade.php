@extends('layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>

    <p><a href="{{ route('members.index') }}">&larr; Kembali ke Daftar Anggota</a></p>

    <form action="{{ route('members.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="name">Nama Lengkap</label><br>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Nama Anggota" style="width: 320px; padding: 5px;" required>
            @error('name')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="email@contoh.com" style="width: 320px; padding: 5px;">
            @error('email')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="phone">Nomor Telepon</label><br>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" style="width: 320px; padding: 5px;">
            @error('phone')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="address">Alamat</label><br>
            <textarea name="address" id="address" rows="3" style="width: 320px; padding: 5px;" placeholder="Alamat tinggal">{{ old('address') }}</textarea>
            @error('address')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" style="padding: 6px 15px;">Simpan Anggota</button>
    </form>
@endsection
