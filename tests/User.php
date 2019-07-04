<?php

namespace Junges\ACL\Tests;

use Junges\ACL\Traits\UsersTrait;
use Illuminate\Notifications\Notifiable;
use Junges\ACL\Traits\ACLWildcardsTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use UsersTrait;
    use ACLWildcardsTrait;
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
