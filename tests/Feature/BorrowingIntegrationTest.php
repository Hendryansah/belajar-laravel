<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    private Member $member;

    private Category $category;

    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->officer = User::factory()->petugas()->create(['name' => 'Petugas Perpustakaan']);
        $this->category = Category::factory()->create(['name' => 'Teknologi Informasi']);
        $this->member = Member::factory()->create(['name' => 'Budi Santoso']);
        $this->book = Book::factory()->create([
            'title' => 'Laravel Mastery',
            'stock' => 5,
            'category_id' => $this->category->id,
        ]);
    }

    public function test_models_relations_work_as_designed(): void
    {
        $borrowing = Borrowing::create([
            'code' => 'PJM-2026-0001',
            'user_id' => $this->officer->id,
            'member_id' => $this->member->id,
            'borrow_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'borrowed',
        ]);
        $borrowing->books()->attach($this->book->id, ['qty' => 2]);

        // Category -> Books
        $this->assertTrue($this->category->books->contains($this->book));
        // Book -> Category
        $this->assertSame($this->category->id, $this->book->category->id);
        // Member -> Borrowings
        $this->assertTrue($this->member->borrowings->contains($borrowing));
        // Borrowing -> Member
        $this->assertSame($this->member->id, $borrowing->member->id);
        // Borrowing -> Officer
        $this->assertSame($this->officer->id, $borrowing->officer->id);
        // Borrowing -> Books
        $this->assertTrue($borrowing->books->contains($this->book));
        $this->assertEquals(2, $borrowing->books->first()->pivot->qty);
        // Book -> Borrowings
        $this->assertTrue($this->book->borrowings->contains($borrowing));
    }

    public function test_borrowing_process_stores_transaction_and_decreases_stock(): void
    {
        $initialStock = $this->book->stock; // 5

        $response = $this->actingAs($this->officer)->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'items' => [
                [
                    'book_id' => $this->book->id,
                    'qty' => 2,
                ],
            ],
        ]);

        $this->assertDatabaseHas('borrowings', [
            'member_id' => $this->member->id,
            'user_id' => $this->officer->id,
            'status' => 'borrowed',
        ]);

        $this->assertDatabaseHas('book_borrowing', [
            'book_id' => $this->book->id,
            'qty' => 2,
        ]);

        $this->book->refresh();
        $this->assertSame($initialStock - 2, $this->book->stock); // 3

        $response->assertSessionHas('success');
    }

    public function test_cannot_borrow_when_requested_qty_exceeds_stock(): void
    {
        $response = $this->actingAs($this->officer)->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'items' => [
                [
                    'book_id' => $this->book->id,
                    'qty' => 10, // Stock is only 5
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseCount('borrowings', 0);

        $this->book->refresh();
        $this->assertSame(5, $this->book->stock);
    }

    public function test_return_process_updates_status_and_increases_stock(): void
    {
        // Pinjam 2 buku terlebih dahulu
        $this->actingAs($this->officer)->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'items' => [
                [
                    'book_id' => $this->book->id,
                    'qty' => 2,
                ],
            ],
        ]);

        $this->book->refresh();
        $this->assertSame(3, $this->book->stock);

        $borrowing = Borrowing::first();

        // Jalankan proses pengembalian
        $returnResponse = $this->actingAs($this->officer)->post(route('borrowings.return', $borrowing));

        $returnResponse->assertSessionHas('success');

        $borrowing->refresh();
        $this->assertSame('returned', $borrowing->status);
        $this->assertNotNull($borrowing->return_date);

        // Stok bertambah kembali
        $this->book->refresh();
        $this->assertSame(5, $this->book->stock);
    }

    public function test_dashboard_displays_real_information_from_database(): void
    {
        // 1 buku, stok 5, 1 kategori, 1 member
        $response = $this->actingAs($this->officer)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Library System Dashboard');
        $response->assertSee((string) $this->book->stock);
        $response->assertSee('Judul Buku');
        $response->assertSee('Total Stok Buku');
        $response->assertSee('Jumlah Anggota');
    }
}
