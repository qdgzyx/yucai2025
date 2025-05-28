<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuantifyItem;

class QuantifyItemPolicy extends Policy
{
    public function update(User $user, QuantifyItem $quantify_item)
    {
        // return $quantify_item->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, QuantifyItem $quantify_item)
    {
        return true;
    }
}
