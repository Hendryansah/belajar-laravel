<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Library System')</title>
</head>
<body style="font-family: sans-serif; margin: 20px; line-height: 1.5; color: #333;">
    <header style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px;">
            <h1 style="margin: 0;">Sistem Informasi Perpustakaan</h1>
            @auth
                <div style="font-size: 14px; text-align: right;">
                    <span>Masuk sebagai: <strong>{{ Auth::user()->name }}</strong> (<em>{{ ucfirst(Auth::user()->role) }}</em>)</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline-block; margin-left: 10px;">
                        @csrf
                        <button type="submit" style="padding: 4px 10px; cursor: pointer; background: #dc3545; color: white; border: none; border-radius: 3px;">Logout</button>
                    </form>
                </div>
            @endauth
        </div>

        @auth
            <nav style="margin-top: 10px; padding: 10px 0; border-bottom: 1px solid #ddd;">
                <a href="{{ route('dashboard') }}" style="margin-right: 10px;">Dashboard</a> |
                <a href="{{ route('books.index') }}" style="margin: 0 10px;">Buku</a> |
                <a href="{{ route('categories.index') }}" style="margin: 0 10px;">Kategori</a> |
                <a href="{{ route('members.index') }}" style="margin: 0 10px;">Anggota</a> |
                <a href="{{ route('borrowings.index') }}" style="margin: 0 10px;">Peminjaman</a>
                @can('manage-users')
                    | <a href="{{ route('users.index') }}" style="margin: 0 10px; color: #d9534f; font-weight: bold;">Pengguna (Admin)</a>
                @endcan
            </nav>
        @endauth
    </header>

    <main>
        @yield('content')
    </main>

    <footer style="margin-top: 40px; border-top: 1px solid #ddd; padding-top: 15px; font-size: 13px; color: #777;">
        <p>ilhamhendryansah &copy; {{ date('Y') }} — Praktikum Web Framework</p>
    </footer>
</body>
</html>
