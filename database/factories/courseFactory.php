<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courseTitles = [
            'Web Development',
            'Data Science',
            'Digital Marketing',
            'Graphic Design',
            'Mobile App Development',
            'Cyber Security',
            'AI',
            'Machine Learning',
            'Network Security',
            'Accounting',
            'Electrical Engineering',
            'Chemical Engineering',
            'Human Resources',
            'Hotel Management',
            'Game Development',
        ];

        $coverImages = [
            'course-1.jpg',
            'course-2.jpg',
            'course-3.jpg',
            'course-4.jpg',
            'course-5.jpg',
            'course-6.jpg',
            'course-7.jpg',
            'course-8.jpg',
            'course-9.jpg',
            'course-10.jpg',
            'course-11.jpg',
            'course-12.jpg',
        ];

        return [
            'title' => $this->faker->randomElement($courseTitles),
            'cover_image' => $this->faker->randomElement($coverImages),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'created_by' => 1, // Assuming the creator's ID is 1, adjust as necessary
        ];
    }
}