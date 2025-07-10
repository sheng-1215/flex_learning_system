<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course;
use App\Models\CUActivity;
use App\Models\Enrollment;
use App\Models\topic;
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
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123',
            'role' => 'admin',
        ]);
        
        User::factory()->create([
            'name' => 'Lyping',
            'email' => 'lyping@gmail.com',
            'password' => '123',
            'role' => 'lecturer',
        ]);

        User::factory()->create([
            'name' => 'alex',
            'email' => 'alex@gmail.com',
            'password' => '123',
            'role' => 'student',
        ]);
        User::factory()->create([
            'name' => 'jane',
            'email' => 'jane@gmail.com',
            'password' => '123',
            'role' => 'student',
        ]);
        User::factory()->create([
            'name' => 'peter',
            'email' => 'peter@gmail.com',
            'password' => '123',
            'role' => 'student',
        ]);
        Course::factory(3)->create();
        
        Enrollment::create([
            'user_id' => 1, // admin
            'course_id' => 1, // Course 1
            'role' => 'admin',
        ]);
        Enrollment::create([
            'user_id' => 2, // Lyping (lecturer)
            'course_id' => 1, // Course 1
            'role' => 'lecturer',
        ]);
        Enrollment::create([
            'user_id' => 3, // alex
            'course_id' => 2, // Course 2
            'role' => 'student',
        ]);
        Enrollment::create([
            'user_id' => 4, // jane
            'course_id' => 3, // Course 3
            'role' => 'student',
        ]);
       
        CUActivity::factory(3)->create()->each(function ($activity) {
            $activity->course_id = Course::inRandomOrder()->first()->id;
            $activity->save();
        });

        topic::factory(3)->create()->each(function ($topic) {
            $topic->cu_id = CUActivity::inRandomOrder()->first()->id;
            $topic->save();
        });
        

    }
}
