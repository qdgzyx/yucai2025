<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuantifyType;

class QuantifyTypesTableSeeder extends Seeder
{
    public function run()
    {
        QuantifyType::factory()->count(10)->create();
    }
}

