<?php

namespace MateusJunges\ACL\Traits;


trait UsersTrait
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
     * @param mixed $groups
     * @return bool
     */
    public function hasGroup($groups)
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
        return collect(array_map(function ($permission) use ($model){
            if (is_string($permission))
                return $model->where('slug', $permission)->first()->id;
            else if (is_numeric($permission))
                return $model->find($permission)->id;
            else if ($permission instanceof $model)
                return $permission->id;
        }, $permissions));
    }

    /**
     * @param array $groups
     * @return mixed
     */
    protected function getAllGroups(array $groups)
    {
        $model = app(config('acl.models.group'));
        return collect(array_map(function ($group) use ($model){
            if ($group instanceof $model)
                return $group->id;
            else if (is_string($group))
                return $model->where('slug', $group)->first()->id;
            else if (is_numeric($group))
                return $model->find($group)->id;
        }, $groups));
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
     * @param mixed $permissions
     * @return mixed
     */
    public function assignPermissions(array $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions->count() == 0)
            return false;
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

    /**
     * Assign a group to a user
     * @param array $groups
     * @return $this|bool
     */
    public function assignGroup(array $groups)
    {
        $groups = $this->getAllGroups($groups);
        if ($groups->count() == 0)
            return false;
        $this->groups()->syncWithoutDetaching($groups);
        return $this;
    }

    /**
     * Revoke user access to a group
     * @param array $groups
     * @return $this|bool
     */
    public function revokeGroup(array $groups)
    {
        $groups = $this->getAllGroups($groups);
        if ($groups->count() == 0)
            return false;
        $this->groups()->detach($groups);
        return $this;
    }

    public function hasAnyPermission(array $permissions)
    {

    }


}