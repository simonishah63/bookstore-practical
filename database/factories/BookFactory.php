<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->title(),
            'author' => fake()->name(),
            'genre' => fake()->text(5),
            'description' => fake()->text(50),
            'isbn' => fake()->randomNumber(),
            'image' => fake()->imageUrl(480, 680, 'any'),
            'published_at' => fake()->date('Y-m-d'),
            'publisher' => fake()->name(),
        ];
    }
}
