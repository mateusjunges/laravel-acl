<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\GroupHasPermissionsTrait;

class GroupHasPermission extends Model
{
    use SoftDeletes, GroupHasPermissionsTrait;

    protected $fillable = [
        'group_id', 'permission_id',
    ];

    protected $dates = ['deleted_at'];

    protected $table;
}
