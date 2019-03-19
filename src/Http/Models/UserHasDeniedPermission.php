<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use MateusJunges\ACL\Traits\UserHasDeniedPermissionsTrait;

class UserHasDeniedPermission extends Model
{
    use UserHasDeniedPermissionsTrait;

    protected $table;
    protected $fillable = [
        'user_id',
        'permission_id',
    ];
    protected $timestamps = false;
}
