<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::withCount('borrowings')->orderBy('name')->get();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        $title = 'Tambah Anggota';

        return view('members.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'nullable|email|max:255|unique:members,email',
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama anggota wajib diisi.',
            'name.min' => 'Nama anggota minimal 3 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar sebagai anggota.',
        ]);

        $validated['code'] = $this->generateMemberCode();

        Member::create($validated);

        return redirect()
            ->route('members.index')
            ->with('success', 'Anggota berhasil ditambahkan dengan kode: '.$validated['code']);
    }

    public function show(Member $member)
    {
        $member->load(['borrowings.books', 'borrowings.officer']);
        $title = 'Detail Anggota';

        return view('members.show', compact('title', 'member'));
    }

    public function edit(Member $member)
    {
        $title = 'Edit Anggota';

        return view('members.edit', compact('title', 'member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('members', 'email')->ignore($member->id)],
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama anggota wajib diisi.',
            'name.min' => 'Nama anggota minimal 3 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar sebagai anggota.',
        ]);

        $member->update($validated);

        return redirect()
            ->route('members.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        if ($member->borrowings()->where('status', 'borrowed')->exists()) {
            return redirect()
                ->route('members.index')
                ->with('error', 'Anggota tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        $member->delete();

        return redirect()
            ->route('members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    private function generateMemberCode(): string
    {
        $count = Member::count() + 1;

        do {
            $code = sprintf('AGT-%04d', $count++);
        } while (Member::where('code', $code)->exists());

        return $code;
    }
}
