<?php

namespace Junges\ACL\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Junges\ACL\Exceptions\GroupDoesNotExistException;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;

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
        $model = app(config('acl.models.group'));
        if (is_numeric($group))
            $group = $model->find($group);
        else if (is_string($group))
            $group = $model->where('slug', $group)->first();
        if ($group != null)
            return null !== $this->groups()->where('slug', $group->slug)->first();
        return false;
    }

    /**
     * Determine if a user has a permission, regardless of whether it is direct or via group
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $model = app(config('acl.models.permission'));
        if (is_numeric($permission))
            $permission = $model->find($permission);
        else if (is_string($permission))
            $permission = $model->where('slug', $permission)->first();
        if($permission != null)
            return (bool) ($this->permissions()->where('slug', $permission->slug)->count())
                || $this->hasPermissionThroughGroup($permission);
        return false;
    }

    /**
     * Determine if a user has a permission directly associated
     * @param $permission
     * @return bool
     */
    public function hasDirectPermission($permission)
    {
        $model = app(config('acl.models.permission'));
        if (is_numeric($permission))
            $permission = $model->find($permission);
        else if (is_string($permission))
            $permission = $model->where('slug', $permission)->first();
        if($permission != null)
            return (bool) ($this->permissions()->where('slug', $permission->slug)->count());
        return false;
    }


    /**
     * Determine if the user has a group which has the required permission
     * @param $permission
     * @return bool
     */
    public function hasPermissionThroughGroup($permission)
    {
        $model = app(config('acl.models.permission'));
        if (is_numeric($permission))
            $permission = $model->find($permission) != null
                ? $model->find($permission)
                : null;
        else if (is_string($permission))
            $permission = $model->where('slug', $permission)->first() != null
                ? $model->where('slug', $permission)->first()
                : null;

        if ($permission != null)
            foreach ($permission->groups as $group) {
                if ($this->groups->contains($group))
                    return true;
            }
        return false;
    }


    /**
     * Retrieves all permissions a user has via groups
     * @return mixed
     */
    public function permissionViaGroups()
    {
        return $this->load(config('acl.tables.groups'), 'groups.permissions')
            ->groups->flatMap(function ($group){
                return $group->permissions;
            })->sort()->values();
    }

    /**
     * Return all the permissions a user has, both directly and via groups
     */
    public function getAllPermissions()
    {
        $permissions = $this->permissions;
        if ($this->groups)
            $permissions = $permissions->merge($this->permissionViaGroups());
        return $permissions->sort()->values();
    }

    /**
     * @param array $permissions
     * @return mixed
     */
    protected function getPermissionIds(array $permissions)
    {
        $model = app(config('acl.models.permission'));
        return collect(array_map(function ($permission) use ($model){
            if (is_numeric($permission))
                $_permission =  $model->find($permission);
            else if (is_string($permission))
                $_permission =  $model->where('slug', $permission)->first();
            else if ($permission instanceof $model)
                $_permission = $permission;
            if (isset($_permission))
                if (!is_null($_permission)) return $_permission->id;
        }, $permissions));
    }

    /**
     * @param array $groups
     * @return mixed
     */
    protected function getGroupIds(array $groups)
    {
        $model = app(config('acl.models.group'));
        return collect(array_map(function ($group) use ($model){
            if ($group instanceof $model)
                $_group = $group;
            else if (is_numeric($group))
                $_group =  $model->find($group);
            else if (is_string($group))
                $_group = $model->where('slug', $group)->first();
            if (isset($_group))
                if (!is_null($_group)) return $group->id;
        }, $groups));
    }

    /**
     * Convert groups to group ids and throws exception if the group does not exist
     *
     * @param $groups
     * @return Collection
     * @throws GroupDoesNotExistException
     */
    private function convertToGroupIds($groups)
    {
        $model = app(config('acl.models.group'));
        $groups = !is_array($groups) ? [$groups] : $groups;
        return collect(array_map(function($group) use ($model){
            if ($group instanceof $model) return $group->id;
            else if (is_numeric($group)){
                $_group = $model->find($group);
                if ($_group instanceof $model) return $_group->id;
                else throw GroupDoesNotExistException::withId($group);
            }
            else if (is_string($group)){
                $_group = $model->where('slug', $group)->first();
                if ($_group instanceof $model) return $_group->id;
                else throw GroupDoesNotExistException::withSlug($group);
            }
        }, $groups));
    }

    /**
     * Check if the specified user is an admin
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasPermission('admin');
    }

    /**
     * Convert permissions to permission ids and throw exception if the permission doesn't exit.
     *
     * @param $permissions
     * @return Collection
     * @throws PermissionDoesNotExistException
     */
    private function convertToPermissionIds($permissions)
    {
        $model = app(config('acl.models.permission'));
        $permissions = !is_array($permissions) ? [$permissions] : $permissions;
        return collect(array_map(function ($permission) use ($model){
            if ($permission instanceof $model) return $permission->id;
            else if (is_numeric($permission)){
                $_permission = $model->find($permission);
                if ($_permission instanceof $model) return $_permission->id;
                else throw PermissionDoesNotExistException::withId($permission);
            }
            else if (is_string($permission)){
                $_permission = $model->where('slug', $permission)->first();
                if ($_permission instanceof $model) return $_permission->id;
                else throw PermissionDoesNotExistException::withSlug($permission);
            }
        }, $permissions));
    }

    /**
     * Give permissions to the user
     * @param mixed $permissions
     * @return mixed
     */
    public function assignPermissions(array $permissions)
    {
        $permissions = $this->convertToPermissionIds($permissions);
        if ($permissions->count() == 0)
            return false;
        $this->permissions()->syncWithoutDetaching($permissions);
        return $this;
    }

    /**
     * Sync user permissions on database.
     * @param array $permissions
     * @return $this|bool
     */
    public function syncPermissions(array $permissions)
    {
        $permissions = $this->convertToPermissionIds($permissions);
        if ($permissions->count() == 0)
            return false;
        $this->permissions()->sync($permissions);
        return $this;

    }

    /**
     * Revoke permissions from the user
     * @param array $permissions
     * @return $this
     */
    public function revokePermissions(array $permissions)
    {
        $permissions = $this->getPermissionIds($permissions);
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
        $groups = $this->convertToGroupIds($groups);
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
        $groups = $this->getGroupIds($groups);
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
            else if (is_numeric($permission))
                return $model->find($permission);
            else if (is_string($permission))
                return $model->where('slug', $permission)->first();
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
            else if (is_numeric($group))
                return $model->find($group);
            else if (is_string($group))
                return $model->where('slug', $group)->first();
        }, $groups);
        foreach ($groups as $group){
            if ($this->hasGroup($group))
                return true;
        }
        return false;
    }

    /**
     * Check if the user has all specified groups
     * @param array $groups
     * @return bool
     */
    public function hasAllGroups(array $groups)
    {
        $model = app(config('acl.models.group', \Junges\ACL\Http\Models\Group::class));
        $groups = array_map(function ($group) use ($model){
            if ($group instanceof $model)
                return $group;
            else if (is_numeric($group))
                return $model->find($group);
            else if (is_string($group))
                return $model->where('slug', $group)->first();
        }, $groups);
        foreach ($groups as $group) {
            if (!$this->hasGroup($group))
                return false;
        }
        return true;
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
            else if (is_numeric($permission))
                return $model->find($permission);
            else if (is_string($permission))
                return $model->where('slug', $permission)->first();
        }, $permissions);


        foreach ($permissions as $permission)
            if (!$this->hasPermission($permission))
                return false;
        return true;
    }


    /**
     * Scope the model query to certain groups only
     *
     * @param Builder $query
     * @param $groups
     * @return Builder
     */
    public function scopeGroup(Builder $query, $groups) : Builder
    {
        $groupModel  = app(config('acl.models.group'));
        if ($groups instanceof Collection)
            $groups = $groups->all();
        if (!is_array($groups))
            $groups = [$groups];

        $groups = array_map(function ($group) use ($groupModel){
            if ($group instanceof $groupModel)
                return $group;
            if (is_numeric($group))
                return $groupModel->find($group);
            else if (is_string($group))
                return $groupModel->where('slug', $group)->first();
        }, $groups);
        return $query->whereHas('groups', function ($query) use ($groups) {
           $query->where(function ($query) use ($groups){
              foreach ($groups as $group) {
                $query->orWhere(config('acl.tables.groups').'.id', $group->id);
              }
           });
        });
    }

    /**
     * Convert permission id or permission slug to permission model
     * @param $permissions
     * @return array
     */
    protected function convertToPermissionModels($permissions)
    {
        $permissionModel = app(config('acl.models.permission'));
        if ($permissions instanceof Collection)
            $permissions = $permissions->all();
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        return array_map(function ($permission) use ($permissionModel){
            if ($permission instanceof $permissionModel)
                return $permission;
            else if (is_numeric($permission))
                return $permissionModel->find($permission);
            else if (is_string($permission))
                return $permissionModel->where('slug', $permission)->first();
        }, $permissions);
    }


    /**
     * Scope the model query to certain permissions only.
     *
     * @param Builder $query
     * @param $permissions
     * @return Builder
     */
    public function scopePermission(Builder $query, $permissions) : Builder
    {
        $permissions = $this->convertToPermissionModels($permissions);

        $groupsWithPermissions = array_unique(array_reduce($permissions, function ($result, $permission){
            return array_merge($result, $permission->groups->all());
        }, []));

        return $query->where(function ($query) use ($permissions, $groupsWithPermissions){
           $query->whereHas('permissions', function ($query) use ($permissions){
               $query->where(function ($query) use ($permissions){
                   foreach ($permissions as $permission) {
                       $query->orWhere(config('acl.tables.permissions').'.id', $permission->id);
                   }
               });
           });
           if (count($groupsWithPermissions) > 0)
               $query->orWhereHas('groups', function ($query) use ($groupsWithPermissions){
                   $query->where(function ($query) use ($groupsWithPermissions){
                       foreach ($groupsWithPermissions as $groupsWithPermission) {
                           $query->orWhere(config('acl.tables.groups').'.id', $groupsWithPermission->id);
                       }
                   });
               });
        });
    }

    /**
     * Revoke all user permissions
     *
     * @return mixed
     */
    public function revokeAllPermissions()
    {
        $this->permissions()->delete();
        return $this;
    }

    /**
     * Revoke all user groups
     *
     * @return mixed
     */
    public function revokeAllGroups()
    {
        $this->groups()->delete();
        return $this;
    }
}
