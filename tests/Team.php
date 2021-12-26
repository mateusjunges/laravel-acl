<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Eloquent\Model;
use Junges\ACL\Concerns\TeamHasGroups;

class Team extends Model
{
    use TeamHasGroups;

    public $timestamps = false;

    protected $table = 'teams';
}