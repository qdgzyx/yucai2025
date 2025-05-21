<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['allBanji'])->map(function ($class) {
            $submitted = $this->data['banjis']->firstWhere('banji.id', $class->id);
            
            return [
                '班级名称' => $class->name,
                '应到人数' => $submitted->total_expected ?? $class->student_count,
                '实到人数' => $submitted->total_actual ?? 0,
                '病假人数' => $submitted->sick_leave_count ?? 0,
                '事假人数' => $submitted->personal_leave_count ?? 0,
                '缺勤人数' => $submitted->absent_count ?? ($class->student_count - ($submitted->total_actual ?? 0))
            ];
        });
    }

    public function headings(): array
    {
        return [
            '班级名称', 
            '应到人数', 
            '实到人数', 
            '病假人数', 
            '事假人数', 
            '缺勤人数'
        ];
    }
}