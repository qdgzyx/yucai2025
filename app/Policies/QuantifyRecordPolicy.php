<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuantifyRecord;

class QuantifyRecordPolicy extends Policy
{
    public function update(User $user, QuantifyRecord $quantify_record)
    {
        // return $quantify_record->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, QuantifyRecord $quantify_record)
    {
        return true;
    }
}
