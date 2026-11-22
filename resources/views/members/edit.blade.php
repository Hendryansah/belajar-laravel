@extends('layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>

    <p><a href="{{ route('members.index') }}">&larr; Kembali ke Daftar Anggota</a></p>

    <form action="{{ route('members.update', $member) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label>Kode Anggota</label><br>
            <input type="text" value="{{ $member->code }}" disabled style="width: 320px; padding: 5px; background: #eee;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="name">Nama Lengkap</label><br>
            <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" style="width: 320px; padding: 5px;" required>
            @error('name')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" style="width: 320px; padding: 5px;">
            @error('email')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="phone">Nomor Telepon</label><br>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $member->phone) }}" style="width: 320px; padding: 5px;">
            @error('phone')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="address">Alamat</label><br>
            <textarea name="address" id="address" rows="3" style="width: 320px; padding: 5px;">{{ old('address', $member->address) }}</textarea>
            @error('address')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" style="padding: 6px 15px;">Perbarui Anggota</button>
    </form>
@endsection
