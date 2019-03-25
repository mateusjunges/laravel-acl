<?php

namespace Junges\ACL;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class ACLAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Define the system permission gates
         */
        config('acl.models.permission') !== null
        ? $permissionModel = app(config('acl.models.permission'))
        : $permissionModel = app(\Junges\ACL\Http\Models\Permission::class);

        if (config('acl.tables.permissions') !== null)
            if (Schema::hasTable(config('acl.tables.permissions')))
                $permissionModel->all()->map(function ($permission){
                    Gate::define($permission->slug, function ($user) use ($permission){
                        return $user->hasPermission($permission) || $user->isAdmin();
                    });
                });


        /**
         * Add blade directives
         */
        Blade::directive('group', function ($group){
           return "<?php if(auth()->check() && auth()->user()->hasGroup({$group})){?>";

        });
        Blade::directive('endgroup', function (){
           return "<?php } ?>";
        });
        Blade::directive('permission', function ($permission){
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})){?>";
        });
       Blade::directive('endpermission', function (){
           return "<?php } ?>";
       });

    }
}