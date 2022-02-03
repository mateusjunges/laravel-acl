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
         | The model you want to use as Permission model must use the MateusJunges\ACL\Traits\PermissionsTrait
         */
        'permission' => Junges\ACL\Models\Permission::class,

        /*
         | The model you want to use as Group model must use the MateusJunges\ACL\Traits\GroupsTrait
         */
        'group'      => Junges\ACL\Models\Group::class,
    ],

    /*
    |--------------------------------------------------------------------------
    |  Route Model Binding
    |--------------------------------------------------------------------------
    |
    | If you would like model binding to use a database column other than id when
    | retrieving a given model class, you may override the getRouteKeyName method
    | on the Eloquent model with yours. The default key used for route model binding
    | in this package is the `id` database column. You can modify it by changing the
    | following configuration:
    |
     */
    'route_model_binding_keys' => [
        'group_model' => 'id',
        'permission_model' => 'id',
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
        'model_has_permissions'       => 'model_has_permissions',
        'model_has_groups'            => 'model_has_groups',
    ],

    'column_names' => [
        'group_pivot_key'      => null,
        'permission_pivot_key' => null,
        'model_morph_key'      => 'model_id',
        'team_foreign_key'     => 'team_id'
    ],

    'teams' => false,

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

    'register_permission_check_method' => true,

    'cache' => [
        /*
         * All permissions are cached for 24 hours by default. If permissions or groups are updated,
         * then the cache is flushed automatically.
         */
        'expiration_time' => DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store permissions.
         */
        'key' => 'junges.acl.cache',

        /*
         * You can optionally specify a cache driver to use for permissions caching using
         * store drivers listed in config/cache.php.
         */
        'store' => 'default'
    ]
];
