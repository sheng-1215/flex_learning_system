<?php

namespace Database\Factories;

use App\Models\CUActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\topic>
 */
class topicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cu_id'=>CUActivity::factory(), // Assuming you have a CUActivity factory
            'title' => $this->faker->word(),
            'file_path' => $this->faker->sentence(),
            'type'=> $this->faker->randomElement(['video', 'document', 'link']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
