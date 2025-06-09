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
    protected $semester;

    // 修改为直接使用Semester模型
    public function __construct(string $semester = null)
    {
        $this->semester = $semester ?? Semester::current()->value;
    }

    public function model(array $row)
    {
        // 验证并获取班级
        $banji = Banji::where('name', trim($row['班级']))->first();
        if (!$banji) {
            throw ValidationException::withMessages([
                '班级' => '班级不存在: '.$row['班级']
            ]);
        }

        // 验证并获取学科
        $subject = Subject::where('name', trim($row['学科']))->first();
        if (!$subject) {
            throw ValidationException::withMessages([
                '学科' => '学科不存在: '.$row['学科']
            ]);
        }

        // 验证并获取教师
        $teacher = User::where('name', trim($row['教师']))->first();
        if (!$teacher) {
            throw ValidationException::withMessages([
                '教师' => '教师不存在: '.$row['教师']
            ]);
        }

        return new TeacherBanjiSubject([
            'banji_id' => $banji->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'semester' => $this->semester
        ]);
    }
}