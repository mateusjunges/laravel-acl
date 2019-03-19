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
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('acl.models.permission'),
            config('acl.tables.user_has_permissions'));
    }

    /**
     * Return all groups assigned to the user
     * @param bool $trashed
     * @return mixed
     */
    public function groups(){
        return $this->belongsToMany(config('acl.models.group'), config('acl.tables.user_has_groups'));
    }


    /**
     * Return all permissions which user does not have access
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deniedPermissions()
    {
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.user_has_denied_permissions'));
    }

    /**
     * Determine if a user has a specific permission
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission){
        return null !== $this->permissions()->where('name', '=', $permission)->first();
    }

    /**
     * Determine if a user has a specific group
     * @param $trashed
     * @param $group
     * @return bool
     */
    public function hasGroup($group){
        return null !== $this->groups()->where('name', $group)->first();
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
     * @return bool|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hasAnyGroup($groups){
        if(is_array($groups))
            return $this->groups()->whereIn('name', $groups);
        return $this->hasGroup('name', $groups);
    }

    /**
     * Determine if a user has a specific denied permission
     * @param $permission
     * @return bool
     */
    public function hasDeniedPermission($permission)
    {
        return null !== $this->deniedPermissions()->where('name', $permission)->first();
    }
}