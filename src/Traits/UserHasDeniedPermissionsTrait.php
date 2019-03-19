<?php

namespace MateusJunges\ACL\Traits;

trait UserHasDeniedPermissionsTrait
{
    /**
     * UserHasDeniedPermissionsTrait constructor.
     */
    public function __construct()
    {
        $this->table = config("acl.tables.user_has_denied_permissions") != ''
            ? config("acl.tables.user_has_denied_permissions")
            : 'user_has_denied_permissions';

    }

    protected $timestamps = false;



}