<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Semester;

class SemesterPolicy extends Policy
{
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
