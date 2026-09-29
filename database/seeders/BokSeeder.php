<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BokSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'pemrogarman PHP',
            'author'=> 'andi',
            'year' => 2004,
            'stock' =>  5,

            'title' => 'dudung pengembara',
            'author'=> 'ddg',
            'year' => 2007,
            'stock' =>  10,

            

            'title' => 'pijah pengembara',
            'author'=> 'budud',
            'year' => 2002,
            'stock' =>  1,

            'title' => 'elmu pengetahuan',
            'author'=> 'budi',
            'year' => 2007,
            'stock' =>  2,

            'title' => 'pendosa agus',
            'author'=> 'agus',
            'year' => 2018,
            'stock' =>  8,

            'title' => 'pemrograman c++',
            'author'=> 'pijah',
            'year' => 2028,
            'stock' =>  3,
        ]);
    }
}
