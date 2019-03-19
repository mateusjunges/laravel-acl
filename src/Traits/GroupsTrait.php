<?php

namespace MateusJunges\ACL\Traits;

trait GroupsTrait
{

    /**
     * GroupsTrait constructor.
     */
    public function __construct()
    {
        $this->table = config('acl.tables.groups') != ''
            ? config('acl.tables.groups')
            : 'groups';
    }

    /**
     * Return all group permissions
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.group_has_permissions'));
    }

    /**
     * Determine if a group has a specific permission
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return null !== $this->permissions()->where('name', $permission)->first();
    }

    /**
     * Return all users who has a group
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(config('acl.models.User'), config('acl.tables.user_has_groups'));
    }

}