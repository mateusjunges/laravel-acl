<?php

namespace MateusJunges\ACL\Traits;

trait GroupHasPermissionsTrait
{
    /**
     * GroupHasPermissionsTrait constructor.
     */
    public function __construct()
    {
        $this->table = config('acl.group_has_permissions') != ''
            ? config('acl.group_has_permissions')
            : 'group_has_permissions';
    }

    protected $timestamps = false;
}