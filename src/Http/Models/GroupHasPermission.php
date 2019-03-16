<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupHasPermission extends Model
{
    use SoftDeletes;

    protected $table = 'group_has_permissions';

    protected $fillable = [
        'group_id', 'permission_id',
    ];

    protected $dates = ['deleted_at'];


}
