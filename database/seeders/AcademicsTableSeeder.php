<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Academic;

class AcademicsTableSeeder extends Seeder
{
    public function run()
    {
        Academic::factory()->count(10)->create();
    }
}

