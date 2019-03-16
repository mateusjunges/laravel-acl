<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHasDeniedPermission extends Model
{
    use SoftDeletes;

    protected $table = 'user_has_denied_permissions';

    protected $fillable = [
        'user_id',
        'permission_id',
    ];

    protected $dates = ['deleted_at'];
}
