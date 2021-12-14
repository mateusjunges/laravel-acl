<?php

namespace Junges\ACL\Providers;

use Exception;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Junges\ACL\AclRegistrar;

class ACLAuthServiceProvider extends ServiceProvider
{
    public function boot(AclRegistrar $permissionLoader)
    {
        $this->registerPolicies();

        if ($this->app->config['acl.register_permission_check_method']) {
            $permissionLoader->forgetPermissionClass();
            $permissionLoader->registerPermissions();
        }

        $this->app->singleton(AclRegistrar::class, fn ($app) => $permissionLoader);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/acl.php',
            'acl'
        );
    }

    /**
     * Check for database connection.
     *
     * @return bool
     */
    protected function checkConnectionStatus(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
