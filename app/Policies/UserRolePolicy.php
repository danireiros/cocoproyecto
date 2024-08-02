<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserRolePolicy
{
    use HandlesAuthorization;

    /**
     * Only admin can update user roles
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return mixed
     */
    public function updateRole(User $user, User $targetUser)
    {
        return $user->role === 'admin';
    }
}
