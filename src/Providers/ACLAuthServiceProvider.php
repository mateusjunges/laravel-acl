<?php

namespace Junges\ACL\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Junges\ACL\AclRegistrar;

class ACLAuthServiceProvider extends ServiceProvider
{
    public function boot(AclRegistrar $permissionLoader)
    {
        if ($this->app->config['acl.register_permission_check_method']) {
            $permissionLoader->forgetPermissionClass();
            $permissionLoader->registerPermissions();
        }

        $this->app->singleton(AclRegistrar::class, fn ($app) => $permissionLoader);
    }
}
