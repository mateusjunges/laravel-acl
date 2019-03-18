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
     * @param $trashed
     * @return mixed
     */
    public function permissions($trashed = false)
    {
        if ($trashed)
            return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.group_has_permissions'));
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.group_has_permissions'))
            ->whereNull(config('acl.tables.group_has_permissions').'.deleted_at');
    }

    /**
     * Determine if a group has a specific permission
     * @param $permission
     * @param $trashed
     * @return bool
     */
    public function hasPermission($permission, $trashed = false)
    {
        return null !== $this->permissions($trashed)->where('name', $permission)->first();
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