<?php

namespace App\Imports;

use App\Models\Banji;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // 处理表头
use Carbon\Carbon; // 新增：引入 Carbon 类

class BanjisImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Banji([
            'id' => $row['id'] ?? null,                // 主键（非自增时需显式赋值）
            'grade_id' => $row['grade_id'],            // 关联年级ID
            'name' => $row['name'],                    // 名称字段
            'student_count' => $row['student_count'], // 转换为整型
            'user_id' => $row['user_id'],              // 操作用户ID
            'created_at' => isset($row['created_at']) 
                ? Carbon::parse($row['created_at'])    // 时间格式标准化
                : now(),
            'updated_at' => isset($row['updated_at']) 
                ? Carbon::parse($row['updated_at']) 
                : now()
                ]);
    }
}