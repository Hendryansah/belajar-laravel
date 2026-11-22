<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_category_seeder_populates_categories(): void
    {
        $this->seed(CategorySeeder::class);

        $this->assertDatabaseHas('categories', [
            'name' => 'Pemrograman',
        ]);
        $this->assertDatabaseHas('categories', [
            'name' => 'Basis Data',
        ]);
        $this->assertGreaterThanOrEqual(10, Category::count());
    }

    public function test_category_has_many_books_relationship(): void
    {
        $category = Category::factory()->create();
        $book1 = Book::factory()->create(['category_id' => $category->id]);
        $book2 = Book::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->books()->exists());
        $this->assertCount(2, $category->books);
        $this->assertTrue($category->books->contains($book1));
        $this->assertTrue($category->books->contains($book2));
    }

    public function test_book_belongs_to_category_relationship(): void
    {
        $category = Category::factory()->create(['name' => 'Web Development']);
        $book = Book::factory()->create(['category_id' => $category->id]);

        $this->assertNotNull($book->category);
        $this->assertSame($category->id, $book->category->id);
        $this->assertSame('Web Development', $book->category->name);
    }

    public function test_book_index_page_displays_category_name(): void
    {
        $category = Category::factory()->create(['name' => 'Kategori Khusus']);
        $book = Book::factory()->create([
            'title' => 'Buku Belajar Laravel',
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertSee('Buku Belajar Laravel');
        $response->assertSee('Kategori Khusus');
    }

    public function test_book_create_page_provides_category_options(): void
    {
        $category = Category::factory()->create(['name' => 'Kategori Pilihan']);

        $response = $this->actingAs($this->admin)->get(route('books.create'));

        $response->assertStatus(200);
        $response->assertSee('Kategori Buku');
        $response->assertSee('Kategori Pilihan');
        $response->assertSee('value="'.$category->id.'"', false);
    }

    public function test_book_edit_page_provides_category_options(): void
    {
        $category = Category::factory()->create(['name' => 'Kategori Terpilih']);
        $book = Book::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->get(route('books.edit', $book));

        $response->assertStatus(200);
        $response->assertSee('Kategori Buku');
        $response->assertSee('Kategori Terpilih');
        $response->assertSee('value="'.$category->id.'"', false);
    }

    public function test_validation_requires_category_id_when_creating_book(): void
    {
        $response = $this->actingAs($this->admin)->post(route('books.store'), [
            'title' => 'Buku Baru',
            'author' => 'Penulis Baru',
            'year' => 2024,
            'stock' => 10,
            'category_id' => '',
        ]);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('books', [
            'title' => 'Buku Baru',
        ]);
    }

    public function test_validation_rejects_non_existent_category_id_when_creating_book(): void
    {
        $response = $this->actingAs($this->admin)->post(route('books.store'), [
            'title' => 'Buku Baru',
            'author' => 'Penulis Baru',
            'year' => 2024,
            'stock' => 10,
            'category_id' => 999999,
        ]);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('books', [
            'title' => 'Buku Baru',
        ]);
    }

    public function test_can_create_book_with_valid_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('books.store'), [
            'title' => 'Buku Sukses',
            'author' => 'Penulis Sukses',
            'year' => 2024,
            'stock' => 5,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'title' => 'Buku Sukses',
            'category_id' => $category->id,
        ]);
    }

    public function test_validation_rejects_non_existent_category_id_when_updating_book(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->put(route('books.update', $book), [
            'title' => $book->title,
            'author' => $book->author,
            'year' => $book->year,
            'stock' => $book->stock,
            'category_id' => 999999,
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_can_update_book_category(): void
    {
        $oldCategory = Category::factory()->create(['name' => 'Kategori Lama']);
        $newCategory = Category::factory()->create(['name' => 'Kategori Baru']);
        $book = Book::factory()->create(['category_id' => $oldCategory->id]);

        $response = $this->actingAs($this->admin)->put(route('books.update', $book), [
            'title' => $book->title,
            'author' => $book->author,
            'year' => $book->year,
            'stock' => $book->stock,
            'category_id' => $newCategory->id,
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'category_id' => $newCategory->id,
        ]);
    }
}
