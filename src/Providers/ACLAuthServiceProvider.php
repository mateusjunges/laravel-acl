<?php

namespace Junges\ACL\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Junges\ACL\AclRegistrar;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Contracts\Permission as PermissionContract;

class ACLAuthServiceProvider extends ServiceProvider
{
    public function boot(AclRegistrar $permissionLoader)
    {
        $config = $this->app->config['acl.models'];

        if (! $config) {
            return;
        }

        $this->app->bind(PermissionContract::class, $config['permission']);
        $this->app->bind(GroupContract::class, $config['group']);

        if ($this->app->config['acl.register_permission_check_method']) {
            $permissionLoader->forgetPermissionClass();
            $permissionLoader->registerPermissions();
        }

        $this->app->singleton(AclRegistrar::class, fn ($app) => $permissionLoader);
    }
}
