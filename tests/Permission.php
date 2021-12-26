<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Eloquent\SoftDeletes;
use Junges\ACL\Concerns\HasGroups;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Events\PermissionSaving;

class Permission extends \Junges\ACL\Models\Permission
{
    protected $visible = [
        'id',
        'name'
    ];
}
