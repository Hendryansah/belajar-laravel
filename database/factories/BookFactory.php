<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'author' => fake()->name(),
            'year' => fake()->numberBetween(1990, (int) date('Y')),
            'stock' => fake()->numberBetween(1, 100),
            'category_id' => Category::factory(),
        ];
    }
}
