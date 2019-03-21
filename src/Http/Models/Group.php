<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\GroupsTrait;

class Group extends Model
{
    use SoftDeletes, GroupsTrait;

    protected $dates = ['deleted_at'];
    protected $table;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];
}
