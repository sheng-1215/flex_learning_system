<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\course>
 */
class courseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=> fake()->sentence(),
            'start_date'=> fake()->date(),
            'end_date'=> fake()->date(),
            'created_by' => 1, // Assuming the creator's ID is 1, adjust as necessary
        ];
    }
}
