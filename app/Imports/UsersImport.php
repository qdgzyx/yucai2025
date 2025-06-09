<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        

        return new User([
            'id' => $row['id'] ?? null,
            'name' => $row['name'],            
            'email' => $row['email'],
            'email_verified_at' => $row['email_verified_at'],
            // 处理密码默认值
            'password' => $row['password'] ? Hash::make($row['password']) : Hash::make('12345678'),
            'remember_token' => \Str::random(60),
            // 处理头像默认值
            'avatar' => $row['avatar'] ?? 'default-avatar.png',
            'introduction' => $row['introduction'] ?? '',
            'subject_id' => $row['subject_id'],
            'banji_id' => $row['banji_id'],
            'notification_count' => $row['notification_count'] ?? 0,
            'last_actived_at' => $row['last_actived_at']
        ]);
    }
}
