<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

/**
 * BookPolicy
 *
 * Kelas Policy ini berfungsi untuk mengatur otorisasi (hak akses) pengguna
 * terhadap tindakan-tindakan pada model Book.
 */
class BookPolicy
{
    /**
     * Menentukan apakah pengguna dapat melihat daftar seluruh buku (index).
     * Baik admin maupun petugas diperbolehkan melihat daftar buku.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'petugas'], true);
    }

    /**
     * Menentukan apakah pengguna dapat melihat rincian suatu buku (show).
     * Baik admin maupun petugas diperbolehkan melihat detail buku.
     */
    public function view(User $user, Book $book): bool
    {
        return in_array($user->role, ['admin', 'petugas'], true);
    }

    /**
     * Menentukan apakah pengguna dapat membuat/menambahkan buku baru (create & store).
     * Admin dan petugas diperbolehkan menginput buku baru ke perpustakaan.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'petugas'], true);
    }

    /**
     * Menentukan apakah pengguna dapat memperbarui data buku (edit & update).
     * Admin dan petugas diperbolehkan memperbarui data buku.
     */
    public function update(User $user, Book $book): bool
    {
        return in_array($user->role, ['admin', 'petugas'], true);
    }

    /**
     * Menentukan apakah pengguna dapat menghapus buku (destroy).
     * Demi keamanan data katalog, penghapusan buku dibatasi HANYA untuk admin.
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Menentukan apakah pengguna dapat memulihkan buku yang telah dihapus (soft delete).
     * Khusus admin.
     */
    public function restore(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Menentukan apakah pengguna dapat menghapus permanen buku dari database.
     * Khusus admin.
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }
}
