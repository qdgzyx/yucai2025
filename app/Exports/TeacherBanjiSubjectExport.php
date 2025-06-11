<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeacherBanjiSubjectExport implements FromCollection, WithHeadings
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function headings(): array
    {
        return [
            '班级名称',
            '学科名称',
            '教师姓名',
        ];
    }

    public function collection()
    {
        return $this->schedules->map(function ($schedule) {
            return [
                '班级名称' => $schedule->banji->name,
                '学科名称' => $schedule->subject->name,
                '教师姓名' => $schedule->user->name,
            ];
        });
    }
}