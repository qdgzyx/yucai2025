<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report;

class ReportPolicy extends Policy
{
    public function update(User $user, Report $report)
    {
        // return $report->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Report $report)
    {
        return true;
    }
}
