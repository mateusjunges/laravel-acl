<?php

namespace Junges\ACL;

use function foo\func;
use Illuminate\Support\Facades\Auth;
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
        $this->registerBladeDirectives();
    }

    /**
     * Add custom blade directives
     */
    private function registerBladeDirectives()
    {

        /**
         * Group directive
         */
        Blade::directive('group', function ($group){
            return "<?php if(auth()->check() && auth()->user()->hasGroup({$group})){?>";

        });
        /**
         * Else group directive
         */
        Blade::directive('elsegroup', function ($group){
            return "<?php }else if(auth()->check() && auth()->user()->hasGroup({$group})){?>";
        });
        /**
         * End group directive
         */
        Blade::directive('endgroup', function (){
            return "<?php } ?>";
        });
        /**
         * Permission directive
         */
        Blade::directive('permission', function ($permission){
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})){?>";
        });

        /**
         * Else permission directive
         */
        Blade::directive('elsepermission', function ($permission){
           return "<?php }else if(auth()->check() && auth()->user()->hasPermission({$permission})){?>";
        });
        /**
         * End permission directive
         */
        Blade::directive('endpermission', function (){
            return "<?php } ?>";
        });

        /**
         * All permissions directive
         */
        Blade::directive('allpermission', function($permissions){
            return "<?php if(auth()->check() && auth()->user()->hasAllPermissions({$permissions})){?>";
        });
        /**
         * End all permissions directive
         */
        Blade::directive('endallpermission', function (){
            return "<?php } ?>";
        });

        /**
         * Any permission directive
         */
        Blade::directive('anypermission', function ($permissions){
            return "<?php if(auth()->check() && auth()->user()->hasAnyPermission({$permissions})){?>)";
        });
        Blade::directive('endanypermission', function (){
            return "<?php } ?>";
        });

    }
}
