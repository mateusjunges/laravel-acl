<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use MateusJunges\ACL\Traits\GroupHasPermissionsTrait;

class GroupHasPermission extends Model
{
    use GroupHasPermissionsTrait;

    protected $fillable = [
        'group_id', 'permission_id',
    ];

    protected $table;
    protected $timestamps = false;
}
