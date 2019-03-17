<?php

namespace MateusJunges\ACL\Http\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the current logged in user can view all registered users
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        $permission = 'permissions.view';
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
     * Determine if the current logged in user can register a new user
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        $permission = 'permissions.create';
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
     * Determine if the current user logged in can update a registered user
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        $permission = 'permissions.update';
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
     * Determine if the current logged in user can delete a user
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        $permission = 'permissions.delete';
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
