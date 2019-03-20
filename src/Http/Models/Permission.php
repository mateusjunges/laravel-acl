<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\PermissionsTrait;

class Permission extends Model
{
    use SoftDeletes, PermissionsTrait;

    protected $table;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'description', 'slug',
    ];
}
