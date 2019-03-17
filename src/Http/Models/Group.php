<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\GroupPermissionTrait;

class Group extends Model
{
    use SoftDeletes, GroupPermissionTrait;

    protected $table = 'groups';

    protected $fillable = [
      'name', 'description',
    ];

    protected $dates = ['deleted_at'];

}
