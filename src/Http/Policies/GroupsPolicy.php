<?php

namespace MateusJunges\ACL\Http\Policies;

use Gate;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupsPolicy
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
     * Determine if the current logged in user can view all registered users
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        $permission = 'groups.view';
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
        $permission = 'groups.create';
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
        $permission = 'groups.update';
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
        $permission = 'groups.restore';
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

    public function permanentlyDelete(User $user)
    {
        $permission = 'groups.permanentlyDelete';
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
     * @param User $user
     * @return bool
     */
    public function restore(User $user)
    {
        $permission = 'groups.delete';
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

    public function viewPermissions(User $user)
    {
        $permission = 'groups.view-permissions';
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
     * @param User $user
     * @return bool
     */
    public function removeGroupPermission(User $user)
    {
        $permission = 'groups.removePermission';
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
     * @return bool
     */
    public function manage()
    {
        if (Gate::allows('groups.view')
            || Gate::allows('groups.create')
            || Gate::allows('groups.update')
            || Gate::allows('groups.delete'))
            return true;
        return false;
    }
}
