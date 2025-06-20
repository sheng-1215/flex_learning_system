<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::factory()->create([
            'name' => 'alex',
            'email' => 'alex@gmail.com',
            'password' => 'alex123',
            'role' => 'student',
        ]);
        User::factory()->create([
            'name' => 'jane',
            'email' => 'jane@gmail.com',
            'password' => 'jane123',
            'role' => 'student',
        ]);
        User::factory()->create([
            'name' => 'peter',
            'email' => 'peter@gmail.com',
            'password' => 'peter123',
            'role' => 'student',
        ]);
        Course::factory(10)->create();
        
        Enrollment::create([
            'user_id' => 1, // alex
            'course_id' => 1, // Course 1
        ]);
        Enrollment::create([
            'user_id' => 2, // alex
            'course_id' => 1, // Course 1
        ]);
        Enrollment::create([
            'user_id' => 3, // alex
            'course_id' => 2, // Course 1
        ]);
       

    }
}
