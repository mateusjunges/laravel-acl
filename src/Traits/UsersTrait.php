<?php

namespace MateusJunges\ACL\Traits;

trait UsersTrait {

    /**
     * UsersTrait constructor.
     */
    public function __construct()
    {
        $this->table = config('acl.tables.users') != ''
            ? config('acl.tables.users')
            : 'users';
    }

    private $permissionClass;

    public function getPermissionClass()
    {
        if (!isset($this->permissionClass))
            $this->permissionClass = app(\MateusJunges\ACL\ACL::class)->getPermissionClass();
        return $this->permissionClass;
    }

    /**
     * Return all user permissions
     * @param $trashed
     * @return mixed
     */
    public function permissions($trashed)
    {
        if ($trashed)
            return $this->belongsToMany(config('acl.models.permission'),
                config('acl.tables.user_has_permissions'));
        return $this->belongsToMany(config('acl.models.permission'), 'user_has_permissions')
            ->whereNull(config('acl.tables.user_has_permissions').'.deleted_at');
    }

    /**
     * Return all groups assigned to the user
     * @param bool $trashed
     * @return mixed
     */
    public function groups($trashed = false){
        if ($trashed)
            return $this->belongsToMany(config('acl.models.group'), config('acl.tables.user_has_groups'));
        return $this->belongsToMany(config('acl.models.group'), config('acl.tables.user_has_groups'))
            ->whereNull(config('acl.tables.user_has_groups').'.deleted_at');
    }


    /**
     * Return all permissions which user does not have access
     * @param bool $trashed
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deniedPermissions($trashed = false)
    {
        if($trashed)
            return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.user_has_denied_permissions'));
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.user_has_denied_permissions'))
            ->whereNull(config('acl.tables.user_has_denied_permissions').'.deleted_at');
    }

    /**
     * Determine if a user has a specific permission
     * @param $trashed
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission, $trashed = false){
        return null !== $this->permissions($trashed)->where('name', '=', $permission)->first();
    }

    /**
     * Determine if a user has a specific group
     * @param $trashed
     * @param $group
     * @return bool
     */
    public function hasGroup($group, $trashed = false){
        return null !== $this->groups($trashed)->where('name', $group)->first();
    }

    /**
     * Determine if a user is an admin
     * @return bool
     */
    public function isAdmin(){
        return $this->hasPermission('admin');
    }

    /**
     * Determine if a user has any group of a group array
     * @param $groups
     * @param $trashed
     * @return bool|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hasAnyGroup($groups, $trashed = false){
        if(is_array($groups))
            return $this->groups($trashed)->whereIn('name', $groups);
        return $this->hasGroup('name', $groups);
    }

    /**
     * Determine if a user has a specific denied permission
     * @param $permission
     * @param bool $trashed
     * @return bool
     */
    public function hasDeniedPermission($permission, $trashed = false)
    {
        return null !== $this->deniedPermissions($trashed)->where('name', $permission)->first();
    }
}