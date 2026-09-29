@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h2>Daftar Buku</h2>
    
    @php $stock = 7; @endphp
    @if($stock > 0)
        <p>Buku tersedia.</p>
    @else
        <p>Buku sedang habis.</p>
    @endif

    <ul>
        @foreach($books as $book)
                <h3>{{ $book->title }}</h3>
                <P>penulis: {{ $book->author }}</P>
                <P>Tahun: {{ $book->year }}</P>
                <P>Stok: {{ $book->stock }}</P>
        @endforeach
    </ul>
@endsection
