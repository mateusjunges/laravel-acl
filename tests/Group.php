<?php

namespace Junges\ACL\Tests;

use Illuminate\Support\Str;
use Junges\ACL\Events\GroupSaving;

class Group extends \Junges\ACL\Models\Group
{
    protected $visible = [
        'id',
        'name'
    ];
}
