<?php

namespace App\Imports;

use App\Models\Banji;
use App\Models\User; // 新增：引入用户模型
use Illuminate\Validation\ValidationException; // 新增：验证异常类
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // 处理表头
use Carbon\Carbon; // 新增：引入 Carbon 类

class BanjisImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 根据班主任姓名查询用户ID
        $headTeacher = User::where('name', trim($row['班主任']))
            ->first();

        if (!$headTeacher) {
            throw ValidationException::withMessages([
                '班主任' => '用户表中找不到该班主任: '.$row['班主任']
            ]);
        }

        return new Banji([
            'id' => $row['id'] ?? null,                // 主键（非自增时需显式赋值）
            'grade_id' => $row['grade_id'],            // 关联年级ID
            'name' => $row['name'],                    // 名称字段
            'student_count' => $row['student_count'], // 转换为整型
            'user_id' => $headTeacher->id,  // 修改：替换原始直接获取user_id的逻辑
            'created_at' => isset($row['created_at']) 
                ? Carbon::parse($row['created_at'])    // 时间格式标准化
                : now(),
            'updated_at' => isset($row['updated_at']) 
                ? Carbon::parse($row['updated_at']) 
                : now()
                ]);
    }
}