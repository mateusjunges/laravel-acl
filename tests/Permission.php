<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Junges\ACL\Concerns\PermissionsTrait;
use Junges\ACL\Events\PermissionSaving;

class Permission extends Model
{
    use SoftDeletes;
    use PermissionsTrait;

    protected $table = 'test_permissions';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'description', 'slug',
    ];

    protected $dispatchesEvents = [
        'creating' => PermissionSaving::class,
    ];

    public function getRouteKeyName()
    {
        return config('acl.route_model_binding_keys.permission_model', 'slug');
    }
}
