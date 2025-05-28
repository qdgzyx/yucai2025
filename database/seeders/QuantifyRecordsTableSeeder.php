<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuantifyRecord;
use App\Models\User; // 引入 User 模型

class QuantifyRecordsTableSeeder extends Seeder
{
    public function run()
    {
        // 获取 users 表中的所有用户 ID
        $userIds = User::pluck('id')->toArray();

        // 检查是否有用户数据
        if (empty($userIds)) {
            throw new \Exception("No users found in the database. Please seed the users table first.");
        }

        // 使用工厂方法生成数据，并为每个记录分配一个随机的 user_id
        QuantifyRecord::factory()
            ->count(10)
            ->state(function () use ($userIds) {
                return [
                    'user_id' => fake()->randomElement($userIds), // 随机选择一个用户 ID
                ];
            })
            ->create();
    }
}