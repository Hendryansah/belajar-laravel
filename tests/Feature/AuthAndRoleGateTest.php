<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthAndRoleGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_books(): void
    {
        $response = $this->get(route('books.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_to_login_when_accessing_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_books(): void
    {
        $user = User::factory()->petugas()->create();

        $response = $this->actingAs($user)->get(route('books.index'));

        $response->assertStatus(200);
    }

    public function test_manage_users_gate_allows_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->assertTrue(Gate::forUser($admin)->allows('manage-users'));
    }

    public function test_manage_users_gate_denies_petugas(): void
    {
        $petugas = User::factory()->petugas()->create();

        $this->assertFalse(Gate::forUser($petugas)->allows('manage-users'));
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengelolaan Pengguna Sistem (Admin Only)');
    }

    public function test_petugas_cannot_access_user_management(): void
    {
        $petugas = User::factory()->petugas()->create();

        $response = $this->actingAs($petugas)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_book_via_book_policy(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($admin)->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_petugas_cannot_delete_book_via_book_policy(): void
    {
        $petugas = User::factory()->petugas()->create();
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($petugas)->delete(route('books.destroy', $book));

        $response->assertStatus(403);
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_navigation_displays_user_management_only_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $petugas = User::factory()->petugas()->create();

        $adminResponse = $this->actingAs($admin)->get(route('dashboard'));
        $adminResponse->assertSee('Pengguna (Admin)');

        $petugasResponse = $this->actingAs($petugas)->get(route('dashboard'));
        $petugasResponse->assertDontSee('Pengguna (Admin)');
    }
}
