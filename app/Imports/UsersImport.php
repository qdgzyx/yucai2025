<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // 处理表头
class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
            
        return new User([
            'id' => $row['id'] ?? null,
            'name' => $row['name'],
            'banji_id' => $row['banji_id'],
            'email' => $row['email'],
            'email_verified_at' => $row['email_verified_at'],
            'password' =>$row['password'],
            'remember_token' => \Str::random(60),
            'avatar' => $row['avatar'],
            'introduction' => $row['introduction'] ?? '',
            'notification_count' => $row['notification_count'] ?? 0,
            'last_actived_at' => $row['last_actived_at']
        ]);
    }
}
