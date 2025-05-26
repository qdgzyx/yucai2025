<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class TeacherBanjiSubject extends Model {
    protected $table = 'teacher_banji_subject';
    protected $guarded = [];


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


}