@extends('layouts.app')

@section('title', $title)

@section('content')
    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        
        <div>
            <label>judul buku</label>
            <input type="text" name="title" placeholder="judul buku" value="{{ old('title') }}">
            
            @error('title')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>penulis</label>
            <input type="text" name="author" placeholder="penulis" value="{{ old('author') }}">

            @error('author')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>tahun</label>
            <input type="number" name="year" placeholder="tahun" value="{{ old('year') }}">

            @error('year')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>stok</label>
            <input type="number" name="stock" placeholder="stok" value="{{ old('stock') }}">

            @error('stock')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>Kategori Buku</label>
            <select name="category_id">
                <option value="">Pilih Kategori</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">
            Simpan
        </button>
    </form>
@endsection