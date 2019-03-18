<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Traits\UserHasGroupsTrait;

class UserHasGroup extends Model
{
    use SoftDeletes, UserHasGroupsTrait;

    /**
     * @var bool|string
     */
    protected $table;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'group_id',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

}
