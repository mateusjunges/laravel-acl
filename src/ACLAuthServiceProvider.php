<?php

namespace MateusJunges\ACL;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use MateusJunges\ACL\Http\Policies\DeniedPermissionsPolicyPolicy;
use MateusJunges\ACL\Http\Policies\GroupsPolicy;
use MateusJunges\ACL\Http\Policies\PermissionsPolicy;
use MateusJunges\ACL\Http\Policies\RolesPolicy;
use MateusJunges\ACL\Http\Policies\UsersPolicy;
use MateusJunges\ACL\Http\Models\Group;
use MateusJunges\ACL\Http\Models\Permission;
use App\User;
use MateusJunges\ACL\Http\Models\UserHasGroup;
use MateusJunges\ACL\Http\Models\UserHasDeniedPermission;

class ACLAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        User::class => UsersPolicy::class,
//        Permission::class => PermissionsPolicy::class,
//        Group::class => GroupsPolicy::class,
//        UserHasGroup::class => RolesPolicy::class,
//        UserHasDeniedPermission::class => DeniedPermissionsPolicyPolicy::class,
    ];

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
           return "<?php if(auth()->check() && auth()->user()->hasGroup({$group})) :";
        });
        Blade::directive('endgroup', function (){
           return "<?php endif; ?>";
        });
        Blade::directive('permission', function ($permission){
            return "<?php if(auth()->user()->check() && auth()->user()->hasPermission({$permission}) :";
        });
       Blade::directive('endpermission', function (){
           return "<?php endif; ?>";
       });

    }
}