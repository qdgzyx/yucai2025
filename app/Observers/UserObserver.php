<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function saving(User $user)
    {
        // 这样写扩展性更高，只有空的时候才指定默认头像
        if (empty($user->avatar)) {
            $user->avatar = '/uploads/images/avatars/202505/21/1_1747784252_nLiuGd2YBq.jpg';
        }
    }
}