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
        'user'                    => \App\User::class,

        /*
         | The model you want to use as Permission model must use the MateusJunges\ACL\Traits\PermissionsTrait
         */
        'permission'              => \MateusJunges\ACL\Http\Models\Permission::class,

        /*
         | The model you want to use as Group model must use the MateusJunges\ACL\Traits\GroupsTrait
         */
        'group'                   => \MateusJunges\ACL\Http\Models\Group::class,

        /*
         | The model you want to use as GroupHasPermission model must use the MateusJunges\ACL\Traits\GroupHasPermissionsTrait
         */
        'GroupHasPermission'      => \MateusJunges\ACL\Http\Models\GroupHasPermission::class,

        /*
         | The model you want to use as UserHasPermissions model must use the MateusJunges\ACL\Traits\UserHasPermissionsTrait
         */
        'UserHasPermission'       => \MateusJunges\ACL\Http\Models\UserHasPermission::class,

        /*
         | The model you want to use as UserHasGroup model must use the MateusJunges\ACL\Traits\UserHasGroup
         */
        'UserHasGroup'            => \MateusJunges\ACL\Http\Models\UserHasGroup::class,

        /*
         | The model you want to use as UserHasDeniedPermission model must use the MateusJunges\ACL\Traits\UserHasDeniedPermissionsTrait
         */
        'UserHasDeniedPermission' => \MateusJunges\ACL\Http\Models\UserHasDeniedPermission::class
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
        'user_has_denied_permissions' => 'user_has_denied_permissions'
    ],
    'app' => [
        'name' => 'ACL'
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the navbar. Each menu item
    | should have a 'text' and and a 'url' or 'route'.
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
     */
    'menu' => [
        [
            'text' => 'Admin',
            'can' => 'users.admin',
            'submenu' => [
                [
                    'text' => 'Usuários',
                    'submenu' => [
                        [
                            'text' => 'Lista de usuários',
                            'can' => 'users.view',
                            'route' => 'users.index'
                        ],
                        [
                            'text' => 'Usuários removidos',
                            'can' => 'users.trashed',
                            'route' => 'users.trashed'
                        ]
                    ]
                ],
                [
                    'text' => 'Permissões',
                    'can' => 'permissions.view',
                    'route' => 'permissions.index',
//                    'submenu' => [
//                        [
//                            'text' => 'Atribuir permissão a grupo',
//                            'can' => 'groups.managePermissions',
//                            'route' => 'groups.'
//                        ]
//                    ]
                ],
                [
                    'text' => 'Grupos',
                    'can' => 'groups.manage',
                    'submenu' => [
                        [
                            'text' => 'Ver grupos',
                            'route' => 'groups.index',
                        ],
                        [
                            'text' => 'Grupos removidos',
                            'route' => 'groups.trashed'
                        ]
                    ],
                ]
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */
    'filters' => [
        \MateusJunges\ACL\Http\Menu\Filters\HrefFilter::class,
        \MateusJunges\ACL\Http\Menu\Filters\ActiveFilter::class,
        \MateusJunges\ACL\Http\Menu\Filters\SubmenuFilter::class,
        \MateusJunges\ACL\Http\Menu\Filters\ClassesFilter::class,
        \MateusJunges\ACL\Http\Menu\Filters\GateFilter::class
    ]
];