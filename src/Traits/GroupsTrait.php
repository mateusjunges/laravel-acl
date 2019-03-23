<?php

namespace MateusJunges\ACL\Traits;


use Illuminate\Database\Eloquent\SoftDeletes;

trait GroupsTrait
{
    use SoftDeletes;
    /**
     * Return all group permissions
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.group_has_permissions'));
    }

    /**
     * Used only to fill the group form
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $model = app(config('acl.models.group'));
        if (is_string($permission))
            $permission = $model->where('slug', $permission)->first();
        else if (is_numeric($permission))
            $permission = $model->find($permission);
        if ($permission != null)
            return null !== $this->permissions()->where('slug', $permission->slug)->first();
        return false;
    }

    /**
     * Return all users who has a group
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(config('acl.models.user'), config('acl.tables.user_has_groups'));
    }

    /**
     * Add permissions to a group
     * @param array $permissions
     * @return $this|bool
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
     * Remove group permissions
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
     * Retrive permission id for each one of the permissions array
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
     * Retrive a user model for each one of the users id array
     * @param array $users
     * @return mixed
     */
    protected function getAllUsers(array $users)
    {
        $model = app(config('acl.models.user'));
        return collect(
            array_map(function ($user) use ($model){
                if ($user instanceof $model)
                    return $user->id;
                else if (is_numeric($user))
                    return $model->find($user)->id;
                else if (is_string($user))
                    return $model->where('name', $user)->first()->id;
            }, $users));
    }

    /**
     * Assign user to group
     * @param array $users
     * @return $this|bool
     */
    public function assignUser(array $users)
    {
        $users = $this->getAllUsers($users);
        if ($users->count() == 0)
            return false;
        $this->users()->syncWithoutDetaching($users);
        return $this;
    }

    /**
     * Remove users from the group
     * @param array $users
     * @return mixed
     *
     */
    public function removeUser(array $users)
    {
        $users = $this->getAllUsers($users);
        if ($users->count() == 0)
            return false;
        $this->users()->detach($users);
        return $this;
    }


    /**
     * Check if the group has any permission of a permission array
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
     * Check if a group has all specified permissions
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