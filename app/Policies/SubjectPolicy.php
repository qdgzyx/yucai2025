<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subject;

class SubjectPolicy extends Policy
{
    public function update(User $user, Subject $subject)
    {
        // return $subject->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Subject $subject)
    {
        return true;
    }
}
