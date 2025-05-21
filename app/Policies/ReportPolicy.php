<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report;

class ReportPolicy extends Policy
{
    public function update(User $user, Report $report)
    {
        return $user->isAuthorOf($report);
    }

    public function destroy(User $user, Report $report)
    {
        return $user->isAuthorOf($report);
    }
}
