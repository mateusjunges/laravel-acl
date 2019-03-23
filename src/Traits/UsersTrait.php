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
     * @param mixed $group
     * @return bool
     */
    public function hasGroup($group)
    {
        return null !== $this->groups()->where('slug', $group->slug)->first();
    }

    /**
     * Determine if a user has a permission, regardless of whether it is direct or via group
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return (bool) ($this->permissions()->where('slug', $permission->slug)->count())
            || $this->hasPermissionThroughGroup($permission);
    }

    /**
     * Determine if a user has a permission directly associated
     * @param $permission
     * @return bool
     */
    public function hasDirectPermission($permission)
    {
        return (bool) $this->permissions()->where('slug', $permission->slug)->count();
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

    /**
     * Check if a user has any permission
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions)
    {
        $model = app(config('acl.models.permission'));
        $permissions = array_map(function ($permission) use ($model){
            if ($permission instanceof $model)
                return $permission;
            else if (is_string($permission))
                return $model->where('slug', $permission)->first();
            else if (is_numeric($permission))
                return $model->find($permission);
        }, $permissions);

        foreach ($permissions as $permission)
            if ($this->hasPermission($permission))
                return true;
        return false;
    }

    /**
     * Check if the user has any group
     * @param array $groups
     * @return bool
     */
    public function hasAnyGroup(array $groups)
    {
        $model = app(config('acl.models.group'));
        $groups = array_map(function ($group) use ($model){
            if ($group instanceof $model)
                return $group;
            else if (is_string($group))
                return $model->where('slug', $group)->fisrt();
            else if (is_numeric($group))
                return $model->find($group)->first();
        }, $groups);
        foreach ($groups as $group){
            if ($this->hasGroup($group))
                return true;
        }
        return false;
    }

    /**
     * Check if the user has all specified permissions
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions)
    {
        $model = app(config('acl.models.permission'));
        $permissions = array_map(function ($permission) use ($model){
            if ($permission instanceof $model)
                return $permission;
            else if (is_string($permission))
                return $model->where('slug', $permission)->first();
            else if (is_numeric($permission))
                return $model->find($permission);
        }, $permissions);

        foreach ($permissions as $permission)
            if (!$this->hasPermission($permission))
                return false;
        return true;
    }




}