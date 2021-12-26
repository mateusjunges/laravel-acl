<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Junges\ACL\Concerns\TeamHasGroups;

class SoftDeletingTeam extends Model
{
    use SoftDeletes;
    use TeamHasGroups;

    public $timestamps = false;

    protected $table = 'teams';
}