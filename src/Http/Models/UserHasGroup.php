<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHasGroup extends Model
{
    use SoftDeletes;

    protected $table = 'user_has_groups';

    protected $fillable = [
        'user_id',
        'group_id',
    ];

    protected $dates = ['deleted_at'];
}
