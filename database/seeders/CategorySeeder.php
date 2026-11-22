<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Pemrograman',
            'Basis Data',
            'Jaringan Komputer',
            'Sistem Informasi',
            'enterprice planing system',
            'proses bisnis',
            'Sains',
            'Teknologi',
            'data maning',
            'computer sains',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
