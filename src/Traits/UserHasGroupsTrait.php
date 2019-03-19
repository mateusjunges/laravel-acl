<?php

namespace MateusJunges\ACL\Traits;

trait UserHasGroupsTrait
{
    /**
     * UserHasGroupsTrait constructor.
     */
    public function __construct()
    {
        $this->table = config('acl.tables.user_has_groups')
            ? '' || config('acl.tables.user_has_groups')
            :  'user_has_groups';
    }
}