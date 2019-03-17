<?php

namespace MateusJunges\ACL\Traits;

trait GroupPermissionTrait {

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
    public function hasPermission($permission, $trashed = false){
        return null !== $this->permissions($trashed)->where('name', $permission)->first();
    }

}