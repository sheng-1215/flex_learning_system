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
        ]);User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123',
            'role' => 'admin',
        ]);
        Course::factory(6)->create();
        Course::factory(3)->create();
        
        // 获取所有用户和课程ID
        $userIds = User::pluck('id')->toArray();
        $courseIds = Course::pluck('id')->toArray();

        // 只在user和course都存在时插入enrollment
        if(count($userIds) > 0 && count($courseIds) > 0) {
            Enrollment::create([
                'user_id' => $userIds[0],
                'course_id' => $courseIds[0],
                'role' => 'student',
            ]);
        }
        if(count($userIds) > 1 && count($courseIds) > 0) {
            Enrollment::create([
                'user_id' => $userIds[1],
                'course_id' => $courseIds[0],
                'role' => 'student',
            ]);
        }
        if(count($userIds) > 2 && count($courseIds) > 1) {
            Enrollment::create([
                'user_id' => $userIds[2],
                'course_id' => $courseIds[1],
                'role' => 'student',
            ]);
        }
        if(count($userIds) > 3 && count($courseIds) > 2) {
            Enrollment::create([
                'user_id' => $userIds[3],
                'course_id' => $courseIds[2],
                'role' => 'student',
            ]);
        }
        if(count($userIds) > 4 && count($courseIds) > 3) {
            Enrollment::create([
                'user_id' => $userIds[4],
                'course_id' => $courseIds[3],
                'role' => 'student',
            ]);
        }
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
