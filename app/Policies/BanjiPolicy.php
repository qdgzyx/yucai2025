<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Banji;

class BanjiPolicy extends Policy
{
    public function update(User $user, Banji $banji)
    {
        // return $banji->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Banji $banji)
    {
        return true;
    }
}
