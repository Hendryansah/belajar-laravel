@extends('layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>

    <p><a href="{{ route('borrowings.index') }}">&larr; Kembali ke Daftar Peminjaman</a></p>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px; border: 1px solid red; padding: 10px; background: #fff5f5;">
            <strong>Terjadi Kesalahan:</strong>
            <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('borrowings.store') }}" method="POST" style="max-width: 600px;">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="member_id"><strong>Anggota Peminjam</strong></label><br>
            <select name="member_id" id="member_id" style="width: 100%; padding: 6px;" required>
                <option value="">-- Pilih Anggota --</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
                        {{ $member->code }} - {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label><strong>Petugas yang Melayani</strong></label><br>
            <input type="text" value="{{ Auth::user()->name }} ({{ Auth::user()->role }})" disabled style="width: 100%; padding: 6px; background: #eee;">
        </div>

        <fieldset style="margin-bottom: 15px; padding: 15px; border: 1px solid #ccc;">
            <legend><strong>Buku yang Dipinjam</strong></legend>

            <div id="items-container">
                <div class="book-row" style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center;">
                    <select name="items[0][book_id]" style="flex: 1; padding: 6px;" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">
                                {{ $book->title }} (Stok: {{ $book->stock }})
                            </option>
                        @endforeach
                    </select>

                    <div style="display: flex; align-items: center; gap: 5px;">
                        <label>Qty:</label>
                        <input type="number" name="items[0][qty]" value="1" min="1" max="100" style="width: 60px; padding: 6px;" required>
                    </div>
                </div>
            </div>

            <button type="button" id="add-book-btn" style="padding: 4px 10px; font-size: 13px;">+ Tambah Buku Lain</button>
        </fieldset>

        <p style="color: #666; font-size: 13px;">
            * Tanggal peminjaman otomatis hari ini. Jatuh tempo pengembalian adalah 7 hari setelah peminjaman.
        </p>

        <button type="submit" style="padding: 8px 20px; font-weight: bold; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px;">
            Simpan Transaksi Peminjaman
        </button>
    </form>

    <script>
        let rowIndex = 1;
        document.getElementById('add-book-btn').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'book-row';
            newRow.style = 'margin-bottom: 10px; display: flex; gap: 10px; align-items: center;';
            newRow.innerHTML = `
                <select name="items[${rowIndex}][book_id]" style="flex: 1; padding: 6px;" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">
                            {{ $book->title }} (Stok: {{ $book->stock }})
                        </option>
                    @endforeach
                </select>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <label>Qty:</label>
                    <input type="number" name="items[${rowIndex}][qty]" value="1" min="1" max="100" style="width: 60px; padding: 6px;" required>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="color: red; background: none; border: 1px solid red; padding: 4px 8px; cursor: pointer;">Hapus</button>
            `;
            container.appendChild(newRow);
            rowIndex++;
        });
    </script>
@endsection
