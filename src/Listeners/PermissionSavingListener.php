<?php

namespace Junges\ACL\Listeners;

use Junges\ACL\Events\PermissionSaving;
use Junges\ACL\Exceptions\PermissionAlreadyExists;

class PermissionSavingListener
{
    public function handle(PermissionSaving $event)
    {
//        $permission = $event->permission;
//        $permissionModel = app(config('acl.models.permission'));
//        $permissionAlreadyExists = $permissionModel
//            ->where('slug', $permission->slug)
//            ->orWhere('name', $permission->name)
//            ->first();
//
//        if (! is_null($permissionAlreadyExists)) {
//            throw PermissionAlreadyExists::create();
//        }
    }
}
