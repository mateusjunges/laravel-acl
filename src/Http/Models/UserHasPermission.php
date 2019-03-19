<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\UserHasPermissionTrait;

class UserHasPermission extends Model
{
    use UserHasPermissionTrait;

    protected $table = 'user_has_permissions';
    protected $fillable = [
        'user_id', 'permission_id',
    ];

}
