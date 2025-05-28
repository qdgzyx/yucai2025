<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuantifyType;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuantifyTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view_quantify_types') || $user->hasRole('admin');
    }

    public function create(User $user)
    {
        return true; // 临时设置为true允许所有用户创建，或者根据实际需求设置权限逻辑
        // 例如：return $user->can('manage_quantify_types') || $user->hasRole('admin');
    }

    public function update(User $user, QuantifyType $quantifyType)
    {
        return $user->can('manage_quantify_types') || $user->hasRole('admin');
    }

    public function delete(User $user, QuantifyType $quantifyType)
    {
        return $user->can('manage_quantify_types') || $user->hasRole('admin');
    }
}