<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grade;

class GradePolicy extends Policy
{
    public function update(User $user, Grade $grade)
    {
        // return $grade->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Grade $grade)
    {
        return true;
    }
}
