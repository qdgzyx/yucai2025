<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Academic;

class AcademicPolicy extends Policy
{
    public function update(User $user, Academic $academic)
    {
        // return $academic->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Academic $academic)
    {
        return true;
    }
}
