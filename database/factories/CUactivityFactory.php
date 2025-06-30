<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CUactivity>
 */
class CUactivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignments = [
            ['title' => 'Web Dev Final Project', 'description' => 'Build a full-stack web app.'],
            ['title' => 'Data Science Report', 'description' => 'Analyze a dataset and report findings.'],
            ['title' => 'Marketing Plan', 'description' => 'Create a digital marketing strategy.'],
            ['title' => 'Design Portfolio', 'description' => 'Submit your best graphic designs.'],
            ['title' => 'Mobile App Demo', 'description' => 'Present a working mobile app.'],
            ['title' => 'Cyber Security Audit', 'description' => 'Perform a security audit on a system.'],
            ['title' => 'Web Dev Quiz', 'description' => 'Answer questions on web technologies.'],
            ['title' => 'Data Science Presentation', 'description' => 'Present a data visualization.'],
            ['title' => 'Marketing Case Study', 'description' => 'Analyze a real-world marketing case.'],
            ['title' => 'Design Challenge', 'description' => 'Complete a timed design task.'],
        ];
        $assignment = $this->faker->randomElement($assignments);

        return [
            'course_id' => Course::inRandomOrder()->first()->id ?? Course::factory(),
            'title' => $assignment['title'],
            'description' => $assignment['description'],
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
