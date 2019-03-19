<?php

namespace MateusJunges\ACL\Traits;

trait UserHasPermissionTrait
{
    /**
     * UserHasPermissionTrait constructor.
     */
    public function __construct()
    {
        $this->table = config('acl.tables.user_has_permissions') != ''
            ? config('acl.tables.user_has_permissions')
            : 'user_has_permissions';
    }

    protected $timestamps = false;
}