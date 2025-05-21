<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    
    protected $fillable = ['date', 'banji_id', 'total_expected', 'total_actual', 'sick_leave_count', 'sick_list', 'personal_leave_count', 'personal_list', 'absent_count', 'absent_list', 'report_status'];
}
