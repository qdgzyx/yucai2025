<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuantifyItem;

class QuantifyItemsTableSeeder extends Seeder
{
    public function run()
    {
        QuantifyItem::factory()->count(10)->create();
    }
}

