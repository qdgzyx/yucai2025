<?php

namespace App\Imports;

use App\Models\GroupBasicInfo;
use App\Models\Banji;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupBasicInfosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 根据班级名称查询班级ID
        $banjiName = trim($row['banji']);
        $banji = Banji::where('name', $banjiName)->first();

        if (!$banji) {
            throw ValidationException::withMessages([
                '班级名称' => '找不到班级: '.$banjiName
            ]);
        }

        return new GroupBasicInfo([
            'banji_id' => $banji->id,
            'name' => trim($row['name']),
            'leader' => trim($row['leader']),
            'members' => trim($row['members'])
        ]);
    }
}