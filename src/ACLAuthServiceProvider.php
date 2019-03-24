<?php

namespace Junges\ACL;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Junges\ACL\Http\Policies\DeniedPermissionsPolicyPolicy;
use Junges\ACL\Http\Policies\GroupsPolicy;
use Junges\ACL\Http\Policies\PermissionsPolicy;
use Junges\ACL\Http\Policies\RolesPolicy;
use Junges\ACL\Http\Policies\UsersPolicy;
use Junges\ACL\Http\Models\UserHasGroup;
use Junges\ACL\Http\Models\UserHasDeniedPermission;

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
        $permissionModel = app(config('acl.models.permission'));
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