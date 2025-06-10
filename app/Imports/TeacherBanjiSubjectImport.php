<?php

namespace App\Imports;

use App\Models\TeacherBanjiSubject;
use App\Models\Banji;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeacherBanjiSubjectImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 新增列名校验
        $requiredColumns = ['subject', 'banji', 'teacher'];
        foreach ($requiredColumns as $col) {
            if (!isset($row[$col])) {
                throw ValidationException::withMessages([
                    $col => "模板缺少必要列，请确认使用最新模板文件。缺失列：$col"
                ]);
            }
        }

        // 验证并获取班级
        $banji = Banji::where('name', trim($row['banji']))->first();
        if (!$banji) {
            throw ValidationException::withMessages([
                'banji' => '班级不存在: '.$row['banji']
            ]);
        }

        // 验证并获取学科（通过学科名称查找ID）
        $subject = Subject::where('name', trim($row['subject']))->first();
        if (!$subject) {
            throw ValidationException::withMessages([
                'subject' => '学科不存在: '.$row['subject']
            ]);
        }

        // 验证并获取教师
        $teacher = User::where('name', trim($row['teacher']))->first();
        if (!$teacher) {
            throw ValidationException::withMessages([
                'teacher' => '教师不存在: '.$row['teacher']
            ]);
        }

        return new TeacherBanjiSubject([
            'banji_id' => $banji->id,
            'subject_id' => $subject->id,
            'user_id' => $teacher->id
           
        ]);
    }
}