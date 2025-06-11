<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Semester;

class SemesterPolicy extends Policy
{
    public function create(User $user)
    {
        // 确认此处的逻辑是否正确，例如：
        return $user->hasRole('admin'); // 示例：只有管理员可以创建学期
    }

    public function update(User $user, Semester $semester)
    {
        // return $semester->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Semester $semester)
    {
        return true;
    }
}
