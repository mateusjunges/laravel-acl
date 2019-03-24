<?php

namespace Junges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Junges\ACL\Traits\GroupsTrait;

class Group extends Model
{
    use GroupsTrait;

    protected $dates = ['deleted_at'];
    protected $table = 'groups';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];
}
