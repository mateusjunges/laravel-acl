<?php

namespace Junges\ACL\Tests;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Junges\ACL\Concerns\HasGroups;

class User extends Authenticatable
{
    use HasGroups;
    use Notifiable;

    protected $fillable = ['name', 'email'];

    public $timestamps = false;

    protected $table = 'users';
}
