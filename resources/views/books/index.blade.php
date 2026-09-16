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
            <li>
                <a href="/books/{{ $book['id'] }}">{{ $book['title'] }}</a> 
                (Penulis: {{ $book['author'] }}, Tahun: {{ $book['year'] }})
            </li>
        @endforeach
    </ul>
@endsection
