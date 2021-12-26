<?php

namespace Junges\ACL\Tests;

class RuntimeGroup extends \Junges\ACL\Models\Group
{
    protected $visible = [
        'id',
        'name',
    ];
}
