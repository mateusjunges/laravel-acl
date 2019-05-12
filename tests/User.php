<?php

namespace Junges\ACL\Test;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Junges\ACL\Traits\UsersTrait;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use UsersTrait;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'test_users';

}
