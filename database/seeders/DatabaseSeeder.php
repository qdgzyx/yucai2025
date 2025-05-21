<?php

namespace  Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersTableSeeder::class);
		$this->call(ReportsTableSeeder::class);
		$this->call(BanjisTableSeeder::class);
		$this->call(GradesTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
        $this->call(RepliesTableSeeder::class);
        $this->call(LinksTableSeeder::class);
    }
}