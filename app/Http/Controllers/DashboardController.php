<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Member;

class DashboardController extends Controller
{
    public function index()
    {
        $bookCount = Book::count();
        $totalStock = Book::sum('stock') ?? 0;
        $categoryCount = Category::count();
        $memberCount = Member::count();
        $borrowedCount = Borrowing::where('status', 'borrowed')->count();
        $returnedCount = Borrowing::where('status', 'returned')->count();
        $recentBorrowings = Borrowing::with(['member', 'books', 'officer'])->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'bookCount',
            'totalStock',
            'categoryCount',
            'memberCount',
            'borrowedCount',
            'returnedCount',
            'recentBorrowings'
        ));
    }
}
