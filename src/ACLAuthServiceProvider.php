<?php

namespace MateusJunges\ACL;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use MateusJunges\ACL\Http\Policies\DeniedPermissionsPolicyPolicy;
use MateusJunges\ACL\Http\Policies\GroupsPolicy;
use MateusJunges\ACL\Http\Policies\PermissionsPolicy;
use MateusJunges\ACL\Http\Policies\RolesPolicy;
use MateusJunges\ACL\Http\Policies\UsersPolicy;
use MateusJunges\ACL\Http\Models\Group;
use MateusJunges\ACL\Http\Models\Permission;
use MateusJunges\ACL\Http\Models\User;
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
        User::class => UsersPolicy::class,
        Permission::class => PermissionsPolicy::class,
        Group::class => GroupsPolicy::class,
        UserHasGroup::class => RolesPolicy::class,
        UserHasDeniedPermission::class => DeniedPermissionsPolicyPolicy::class,

    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Users gates
        Gate::resource('users', UsersPolicy::class, [
            'create' => 'create',
            'view' => 'view',
            'update' => 'update',
            'delete' => 'delete',
            'admin' => 'admin',
            'viewPermissions' => 'viewPermissions',
        ]);

        //Permissions gates
        Gate::resource('permissions', PermissionsPolicy::class, [
            'create' => 'create',
            'view' => 'view',
            'update' => 'update',
            'delete' => 'delete',
        ]);

        //Groups gates
        Gate::resource('groups', GroupsPolicy::class, [
            'create' => 'create',
            'view' => 'view',
            'update' => 'update',
            'delete' => 'delete',
        ]);

        //Roles gates
        Gate::resource('roles', RolesPolicy::class, [
            'create' => 'create',
            'view' => 'view',
            'update' => 'update',
            'delete' => 'delete',
        ]);

        //Denied Permissions gates
        Gate::resource('deniedPermissons', DeniedPermissionsPolicyPolicy::class, [
            'create' => 'create',
            'view' => 'view',
            'update' => 'update',
            'delete' => 'delete',
        ]);
    }
}