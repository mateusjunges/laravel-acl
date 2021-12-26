<?php

namespace Junges\ACL\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Junges\ACL\Concerns\HasGroups;

class Manager extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasGroups;
    use Authorizable;
    use Authenticatable;

    protected $fillable = ['email'];

    public $timestamps = false;

    protected $table = 'users';

    public function guardName()
    {
        return 'jwt';
    }

    protected $guard_name = 'web';
}