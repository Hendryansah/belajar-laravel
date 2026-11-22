<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            MemberSeeder::class,
        ]);

        // Tambah contoh buku jika belum ada buku di DB
        if (Book::count() === 0) {
            $pemrograman = Category::where('name', 'Pemrograman')->first();
            $basisData = Category::where('name', 'Basis Data')->first();

            if ($pemrograman) {
                Book::create([
                    'title' => 'Pemrograman Web dengan Laravel',
                    'author' => 'Taylor Otwell',
                    'year' => 2024,
                    'stock' => 10,
                    'category_id' => $pemrograman->id,
                ]);

                Book::create([
                    'title' => 'Belajar Modern PHP 8',
                    'author' => 'Rasmus Lerdorf',
                    'year' => 2023,
                    'stock' => 8,
                    'category_id' => $pemrograman->id,
                ]);
            }

            if ($basisData) {
                Book::create([
                    'title' => 'Desain dan Manajemen Basis Data Relasional',
                    'author' => 'C.J. Date',
                    'year' => 2022,
                    'stock' => 5,
                    'category_id' => $basisData->id,
                ]);
            }
        }
    }
}
