<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $subject = Subject::where('name', trim($row['subject']))->first();

        if (!$subject) {
            throw ValidationException::withMessages([
                '学科' => '找不到对应的学科名称: ' . $row['subject']
            ]);
        }

        return new User([
            'id' => $row['id'] ?? null,
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => $row['password'] ? Hash::make($row['password']) : Hash::make('12345678'),
            'avatar' => $row['avatar'] ?? 'default-avatar.png',
            'introduction' => $row['introduction'] ?? '',
            'subject_id' => $subject->id,
            'banji_id' => $row['banji_id'],
            'notification_count' => $row['notification_count'] ?? 0,
            'last_actived_at' => $row['last_actived_at']
        ]);
    }
}