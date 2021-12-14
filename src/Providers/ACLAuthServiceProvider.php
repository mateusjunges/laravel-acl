<?php

namespace Junges\ACL\Providers;

use Exception;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\Models\Permission;

class ACLAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        config('acl.models.permission') !== null
        ? $permissionModel = app(config('acl.models.permission'))
        : $permissionModel = app(Permission::class);

        if ($this->checkConnectionStatus()) {
            if (config('acl.tables.permissions') !== null) {
                if (Schema::hasTable(config('acl.tables.permissions'))) {
                    $permissionModel->all()->map(function ($permission) {
                        Gate::define($permission->slug, function ($user) use ($permission) {
                            return $user->hasPermission($permission) || $user->isAdmin();
                        });
                    });
                }
            }
        }
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
