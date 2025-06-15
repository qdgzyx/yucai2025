<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    
    protected $fillable = ['date', 'banji_id', 'total_expected', 'total_actual', 'sick_leave_count', 'sick_list', 'personal_leave_count', 'personal_list', 'absent_count', 'absent_list', 'report_status'];
// app/Models/Report.php

    /**
     * 获取报告所属的班级
     */
    public function banji()
    {
        return $this->belongsTo(\App\Models\Banji::class);
    }
}
