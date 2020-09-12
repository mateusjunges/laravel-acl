# Getting started

## Installation
 
To get started with laravel-acl, use Composer to add the package to your project's dependencies:

```bash
composer require mateusjunges/laravel-acl
```

Or add this line in your `composer.json`, inside of the `require` section:

```json
{
    "require": {
        "mateusjunges/laravel-acl": "2.4.*"
    }
}
```

> For Laravel v5.5 or lower, use the version 2.0 of this package:
>```json
>{
>    "require": {
>        "mateusjunges/laravel-acl": "2.0.*"
>    }
>}
>```

then run `composer install`.


## Setup


After installing the laravel-acl package, register the service provider in
`config/app.php` configuration file:

> Optional in Laravel 5.5 or above

```php
'providers' => [
    Junges\ACL\ACLServiceProvider::class,
    Junges\ACL\ACLAuthServiceProvider::class,
    Junges\ACL\ACLEventsServiceProvider::class,
];
```

### Install using `acl:install` command

You can install this package by running the provided install command:
```bash
php artisan acl:install
```

After run this command, the package installation is done. Proceed to the [usage](#usage) section.

### Step by step installation

All migrations required for this package are already included. If you
need to customize the tables, you can publish [the migrations](https://github.com/mateusjunges/laravel-acl/tree/master/src/database/migrations)
with:

```bash
php artisan vendor:publish --provider="Junges\ACL\ACLServiceProvider" --tag="acl-migrations"
```
and set the `config` for `custom_migrations` to `true`, which is false by default. 

```php
'custom_migrations' => true,
```

After the migrations has been published you can create the tables on your database by running the migrations:
```bash
php artisan migrate
```
If you change the table names on migrations, please
publish the config file and update the tables array.
You can publish the config file with:

```bash
php artisan vendor:publish --provider="Junges\ACL\ACLServiceProvider" --tag="acl-config"
```

When published, the [`config/acl.php`](https://github.com/mateusjunges/laravel-acl/blob/master/config/acl.php) config file contains:

```php
<?php

    return [

        /*
        |--------------------------------------------------------------------------
        |  Models
        |--------------------------------------------------------------------------
        |
        | When using this package, we need to know which
        | Eloquent Model should be used
        | to retrieve your groups and permissions.
        | Of course, it is just the basics models
        | needed, but you can use whatever you like.
        |
        */

        'models' => [
            /*
             | The model you want to use as User Model must use \Junges\ACL\Traits\UsersTrait
             */
            'user'  => \App\User::class,

            /*
             | The model you want to use as Permission model must use the \Junges\ACL\Traits\PermissionsTrait
             */
            'permission'  => Junges\ACL\Http\Models\Permission::class,

            /*
             | The model you want to use as Group model must use the \Junges\ACL\Traits\GroupsTrait
             */
            'group'  => Junges\ACL\Http\Models\Group::class,
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

    ];
```

## Tests

Run `composer test` to test this package.
