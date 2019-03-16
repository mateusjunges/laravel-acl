<?php

return [
    'models' => [
        'user' => \MateusJunges\ACL\Http\Models\User::class,
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
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
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
                    'can' => 'users.view',
                    'route' => 'users.index',
                ],
                [
                    'text' => 'Permissões',
                    'can' => 'permissions.view',
                    'route' => 'permissions.index',
                ],
                [
                    'text' => 'Grupos',
                    'can' => 'groups.view',
                    'route' => 'groups.index',
                ],
            ]
        ]
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