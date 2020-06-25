<?php

namespace Junges\ACL\Listeners;

use Junges\ACL\Events\PermissionSaving;
use Junges\ACL\Exceptions\PermissionAlreadyExistsException;
use Junges\ACL\Helpers\Config;

class PermissionSavingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PermissionSaving $event
     *
     * @return void
     */
    public function handle(PermissionSaving $event)
    {
        $permission = $event->permission;
        $permissionModel = app(Config::get('models.permission'));
        $permissionAlreadyExists = $permissionModel
            ->where('slug', $permission->slug)
            ->orWhere('name', $permission->name)
            ->first();
        if (! is_null($permissionAlreadyExists)) {
            throw PermissionAlreadyExistsException::create();
        }
    }
}
