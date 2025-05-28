<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemestersTableSeeder extends Seeder
{
    public function run()
    {
        Semester::factory()->count(10)->create();
    }
}

