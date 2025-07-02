<?php

namespace Database\Seeders;

use App\Models\assignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class assignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        assignment::factory(2)->create([
            'cu_id' => \App\Models\CUActivity::inRandomOrder()->first()->id,
            
        ]);
    }
}
