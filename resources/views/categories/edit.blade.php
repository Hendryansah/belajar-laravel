@extends('layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>

    <p><a href="{{ route('categories.index') }}">&larr; Kembali ke Daftar Kategori</a></p>

    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="name">Nama Kategori</label><br>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" style="width: 300px; padding: 5px;" required>

            @error('name')
                <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" style="padding: 6px 15px;">Perbarui Kategori</button>
    </form>
@endsection
