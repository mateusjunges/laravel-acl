<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Junges\ACL\Concerns\HasGroups;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Events\PermissionSaving;

class Permission extends \Junges\ACL\Models\Permission implements PermissionContract
{
    use SoftDeletes;
    use HasGroups;

    protected $table = 'permissions';

    protected $dates = ['deleted_at'];

   protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'creating' => PermissionSaving::class,
    ];

    public function getRouteKeyName(): string
    {
        return config('acl.route_model_binding_keys.permission_model', 'slug');
    }
}
