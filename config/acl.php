<?php

return [

    /*
    |--------------------------------------------------------------------------
    |  Models
    |--------------------------------------------------------------------------
    |
    | When using this package, we need to know which Eloquent Model should be used
    | to retrieve your groups and permissions. Of course, it is just the basics models
    | needed, but you can use whatever you like.
    |
     */
    'models' => [
        /*
         | The model you want to use as User Model must use MateusJunges\ACL\Traits\UsersTrait
         */
        'user'       => \App\Models\User::class,

        /*
         | The model you want to use as Permission model must use the MateusJunges\ACL\Traits\PermissionsTrait
         */
        'permission' => Junges\ACL\Http\Models\Permission::class,

        /*
         | The model you want to use as Group model must use the MateusJunges\ACL\Traits\GroupsTrait
         */
        'group'      => Junges\ACL\Http\Models\Group::class,
    ],

    /*
    |--------------------------------------------------------------------------
    |  Route Model Binding
    |--------------------------------------------------------------------------
    |
    | If you would like model binding to use a database column other than id when
    | retrieving a given model class, you may override the getRouteKeyName method
    | on the Eloquent model with yours. The default key used for route model binding
    | in this package is the `slug` database column. You can modify it by changing the
    | following configuration:
    |
     */
    'route_model_binding_keys' => [
        'group_model' => 'slug',
        'permission_model' => 'slug',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    | Specify the basics authentication tables that you are using.
    | Once you required this package, the following tables are
    | created by default when you run the command
    |
    | php artisan migrate
    |
    | If you want to change this tables, please keep the basic structure unchanged.
    |
     */
    'tables' => [
        'groups'                      => 'groups',
        'permissions'                 => 'permissions',
        'users'                       => 'users',
        'group_has_permissions'       => 'group_has_permissions',
        'user_has_permissions'        => 'user_has_permissions',
        'user_has_groups'             => 'user_has_groups',
    ],

    /*
     |
     |If you want to customize your tables, set this flag to "true"
     | */
    'custom_migrations' => false,

    /*
    |
    | If you want to customize the admin-permission, you can change it here.
    | By default, it is set to 'admin'.
    */
    'admin_permission' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Ignition Solution Suggestions
    |--------------------------------------------------------------------------
    |
    | To enable the ignition solutions for laravel-acl, set this flag to true.
    |
    | The solutions will then be automatically registered with ignition if its installed.
    |
    */
    'offer_solutions' => false,
];
