<?php

namespace MateusJunges\ACL\Traits;

trait HasPermissionTrait
{
    /**
     * Return all user groups
     * @return mixed
     */
    public function groups()
    {
        return $this->belongsToMany(config('acl.models.group'), config('acl.tables.user_has_groups'));
    }

    /**
     * Return all user permissions
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.user_has_permissions'));
    }

    /**
     * Determine if a user has the specified group
     * @param mixed ...$groups
     * @return bool
     */
    public function hasGroup( ... $groups)
    {
        foreach ($groups as $group) {
            if ($this->groups->contains('slug', $group))
                return true;
        }
        return false;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return (bool) ($this->permissions->where('slug', $permission->slug)->count())
            || $this->hasPermissionThroughGroup($permission);
    }


    /**
     * Determine if the user has a group which has the required permission
     * @param $permission
     * @return bool
     */
    public function hasPermissionThroughGroup($permission)
    {
        foreach ($permission->groups as $group) {
            if ($this->groups->contains($group))
                return true;
        }
        return false;
    }

    /**
     * @param array $permissions
     * @return mixed
     */
    protected function getAllPermissions(array $permissions)
    {
        $model = app(config('acl.models.permission'));
        return $model->whereIn('id', $permissions)->get();
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->can('admin');
    }

    /**
     * Give permissions to the user
     * @param mixed ...$permissions
     * @return $this
     */
    public function givePermissions(array $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions === null)
            return $this;
        $this->permissions()->syncWithoutDetaching($permissions);
        return $this;
    }

    /**
     * Revoke permissions from the user
     * @param array $permissions
     * @return $this
     */
    public function revokePermissions(array $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }



}