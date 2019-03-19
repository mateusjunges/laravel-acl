<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use MateusJunges\ACL\Traits\UserHasPermissionTrait;

class UserHasPermission extends Model
{
    use UserHasPermissionTrait;

    protected $table;
    protected $fillable = [
        'user_id', 'permission_id',
    ];
    protected $timestamps = false;

}
