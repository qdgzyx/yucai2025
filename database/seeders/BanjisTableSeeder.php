<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banji;

class BanjisTableSeeder extends Seeder
{
    public function run()
    {
        Banji::factory()->count(10)->create();
    }
}

