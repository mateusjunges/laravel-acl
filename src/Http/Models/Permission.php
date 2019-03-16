<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $table = 'permissions';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'description', 'nickname',
    ];

}
