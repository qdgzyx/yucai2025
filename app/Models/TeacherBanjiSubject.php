<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class TeacherBanjiSubject extends Model {
    protected $table = 'teacher_banji_subject';
    protected $guarded = [];
    // 新增 semester_id 字段到模型的可填充属性
    protected $fillable = ['banji_id', 'subject_id', 'user_id', 'semester_id'];


    // 定义与 User 的关联
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 定义与 Subject 的关联
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // 定义与 Banji 的关联 
    public function banji()
    {
        return $this->belongsTo(Banji::class, 'banji_id');
    }

    // 新增学期关联
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

}