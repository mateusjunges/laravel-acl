<?php

namespace MateusJunges\ACL\Http\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeniedPermissionsPolicyPolicy
{

    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the current logged in user can view the denied permission of one specific user
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        $permission = 'deniedPermissions.view';
        if ($user->hasDeniedPermission($permission))
            return false;
        if ($user->hasPermission($permission) || $user->isAdmin())
            return true;
        $groups = $user->groups;
        foreach ($groups as $group) {
            if ($group->hasPermission($permission) || $group->hasPermission('admin'))
                return true;
        }
        return false;
    }

    /**
     * Determine if a user can deny a permission to any user
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        $permission = 'deniedPermissions.create';
        if ($user->hasDeniedPermission($permission))
            return false;
        if ($user->hasPermission($permission) || $user->isAdmin())
            return true;
        $groups = $user->groups;
        foreach ($groups as $group) {
            if ($group->hasPermission($permission) || $group->hasPermission('admin'))
                return true;
        }
        return false;
    }


    /**
     * Determine if a user can remove a permission lock
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        $permission = 'deniedPermissions.delete';
        if ($user->hasDeniedPermission($permission))
            return false;
        if ($user->hasPermission($permission) || $user->isAdmin())
            return true;
        $groups = $user->groups;
        foreach ($groups as $group) {
            if ($group->hasPermission($permission) || $group->hasPermission('admin'))
                return true;
        }
        return false;
    }

}
