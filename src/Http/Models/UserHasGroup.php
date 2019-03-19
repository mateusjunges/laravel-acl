<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use MateusJunges\ACL\Traits\UserHasGroupsTrait;

class UserHasGroup extends Model
{
    use UserHasGroupsTrait;

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

    protected $timestamps = false;

}
