<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $borrowings = Borrowing::with(['member', 'officer', 'books'])
            ->when(in_array($status, ['borrowed', 'returned'], true), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->get();

        return view('borrowings.index', compact('borrowings', 'status'));
    }

    public function create(): View
    {
        $members = Member::orderBy('name')->get();
        $books = Book::where('stock', '>', 0)->orderBy('title')->get();
        $title = 'Catat Peminjaman Buku';

        return view('borrowings.create', compact('members', 'books', 'title'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'member_id' => 'required|integer|exists:members,id',
        ], [
            'member_id.required' => 'Anggota peminjam wajib dipilih.',
            'member_id.exists' => 'Anggota yang dipilih tidak ditemukan.',
        ]);

        // Mendukung input array 'items' maupun single 'book_id'
        $items = [];
        if ($request->has('items') && is_array($request->input('items'))) {
            $items = $request->input('items');
        } elseif ($request->filled('book_id')) {
            $items = [
                [
                    'book_id' => (int) $request->input('book_id'),
                    'qty' => (int) ($request->input('qty', 1)),
                ],
            ];
        }

        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => 'Minimal harus memilih 1 buku untuk dipinjam.',
            ]);
        }

        $bookIds = collect($items)->pluck('book_id');
        if ($bookIds->unique()->count() !== $bookIds->count()) {
            throw ValidationException::withMessages([
                'items' => 'Buku yang sama tidak boleh dipilih lebih dari sekali dalam satu peminjaman.',
            ]);
        }

        foreach ($items as $item) {
            $qty = isset($item['qty']) ? (int) $item['qty'] : 1;
            if ($qty < 1) {
                throw ValidationException::withMessages([
                    'items' => 'Jumlah pinjam minimal 1 eksemplar.',
                ]);
            }

            $book = Book::find($item['book_id'] ?? null);
            if (! $book) {
                throw ValidationException::withMessages([
                    'items' => 'Buku yang dipilih tidak ditemukan.',
                ]);
            }

            if ($book->stock < $qty) {
                throw ValidationException::withMessages([
                    'items' => "Stok buku \"{$book->title}\" tidak mencukupi. Sisa stok: {$book->stock}.",
                ]);
            }
        }

        $borrowing = DB::transaction(function () use ($request, $items) {
            $borrowing = Borrowing::create([
                'code' => $this->generateBorrowingCode(),
                'user_id' => auth()->id() ?? 1,
                'member_id' => $request->input('member_id'),
                'borrow_date' => now()->toDateString(),
                'due_date' => now()->addDays(7)->toDateString(),
                'status' => 'borrowed',
            ]);

            foreach ($items as $item) {
                $qty = isset($item['qty']) ? (int) $item['qty'] : 1;
                $borrowing->books()->attach($item['book_id'], ['qty' => $qty]);
                Book::whereKey($item['book_id'])->decrement('stock', $qty);
            }

            return $borrowing;
        });

        return redirect()
            ->route('borrowings.show', $borrowing)
            ->with('success', "Peminjaman {$borrowing->code} berhasil disimpan dan stok buku telah dikurangi.");
    }

    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['member', 'officer', 'books']);
        $title = "Detail Transaksi {$borrowing->code}";

        return view('borrowings.show', compact('borrowing', 'title'));
    }

    public function return(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->isReturned()) {
            return redirect()
                ->route('borrowings.show', $borrowing)
                ->with('error', 'Transaksi peminjaman ini sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'return_date' => now()->toDateString(),
                'status' => 'returned',
            ]);

            foreach ($borrowing->books as $book) {
                Book::whereKey($book->id)->increment('stock', $book->pivot->qty);
            }
        });

        return redirect()
            ->route('borrowings.show', $borrowing)
            ->with('success', "Pengembalian transaksi {$borrowing->code} berhasil dicatat dan stok buku telah dikembalikan.");
    }

    private function generateBorrowingCode(): string
    {
        $year = now()->year;
        $count = Borrowing::whereYear('created_at', $year)->count() + 1;

        do {
            $code = sprintf('PJM-%d-%04d', $year, $count++);
        } while (Borrowing::where('code', $code)->exists());

        return $code;
    }
}
