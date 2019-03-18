<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\UserHasDeniedPermissionsTrait;

class UserHasDeniedPermission extends Model
{
    use SoftDeletes, UserHasDeniedPermissionsTrait;

    protected $table;
    protected $fillable = [
        'user_id',
        'permission_id',
    ];

    protected $dates = ['deleted_at'];



}
