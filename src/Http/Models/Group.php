<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\GroupsTrait;

class Group extends Model
{
    use SoftDeletes, GroupsTrait;

    protected $table;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    /**]
     * @var array
     */
    protected $dates = ['deleted_at'];
}
