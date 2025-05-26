<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Assignment;

class AssignmentPolicy extends Policy
{
    // public function update(User $user, Assignment $assignment)
    // {
    //     // return $assignment->user_id == $user->id;
    //     return true;
    // }

    public function destroy(User $user, Assignment $assignment)
    {
        return true;
    }
    
    public function update(User $user, Assignment $assignment) {
    return $user->id === $assignment->user_id;
    }
}
