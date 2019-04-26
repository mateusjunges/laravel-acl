<p align="center">
<a href="https://packagist.org/packages/mateusjunges/laravel-acl" target="_blank"><img src="https://poser.pugx.org/mateusjunges/laravel-acl/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/mateusjunges/laravel-acl" target="_blank"><img src="https://poser.pugx.org/mateusjunges/laravel-acl/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/mateusjunges/laravel-acl" target="_blank"><img src="https://poser.pugx.org/mateusjunges/laravel-acl/license.svg" alt="License"></a>
</p>

# Laravel ACL

This package allows you to manage user permissions and groups in a database.

* [Installation](#installation)
* [Usage](#usage)
    * [Check for permissions](#checking-for-permissions)
    * [Syncing user permissions](#syncing-user-permissions)
    * [Syncing group permissions](#syncing-group-permissions)
    * [Blade and permissions](#blade-and-permissions)
        * [Using package custom blade directives](#using-package-custom-blade-directives)
    * [Using a middleware](#using-a-middleware)
    * [Using artisan commands](#using-artisan-commands)
    * [Extending and replacing models](#extending-and-replacing-models)



## Installation

To get started with laravel-acl, use Composer to add the package to your project's dependencies:

``` bash
composer require mateusjunges/laravel-acl
```

Or add this line in your `composer.json`, inside of the `require` section:

``` json
{
    "require": {
        "mateusjunges/laravel-acl": "1.0.*",
    }
}
```
then run ` composer install `

After installing the laravel-acl package, register the service provider in
`config/app.php` configuration file:

> Optional in Laravel 5.5 or above

```php
'providers' => [
    Junges\ACL\ACLServiceProvider::class,
    Junges\ACL\ACLAuthServiceProvider::class,
];
```
All migrations required for this package are already included. If you
need to customize the tables, you can publish [the migrations](https://github.com/mateusjunges/laravel-acl/tree/master/src/database/migrations)
with:

```bash
php artisan vendor:publish --provider="Junges\ACL\ACLServiceProvider::class" --tag="migrations"
```

After the migrations has been published you can create the tables on your database by running the migrations:
```bash
php artisan migrate
```
If you change the table names on migrations, please
publish the config file and update the tables array.
You can publish the config file with:

```bash
php artisan vendor:publish --provider="Junges\ACL\ACLServiceProvider::class" --tag="config"
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

    ];
```

## Usage

First of all, use the `UsersTrait.php` on your `User` model:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Junges\ACL\Traits\UsersTrait;

class User extends Authenticatable
{
    use UsersTrait;

    //
}
```

You can add permissions to a user using the function below,
using as parameter an array of
permissions slugs, permissions ids or instance of permissions model.
Beside that, you can also combine this 3 ways, using a array with a permission id,
one instance of permission model and a permission slug too.
```php
//With permission slugs:
$user->assignPermissions(['permission-slug-1', 'permission-slug-2']);

//With permission ids:
$user->assignPermissions([1, 2, 3]);

//With instances of permission model:
$user->assignPermissions([Permission::find(1), Permission::find(2)]);

//With the three ways above combined:
$user->assignPermissions([1, 'permission-slug', Permission::find(1)]);
```
Like as add permissions to user, you can add permissions to groups.
To do this, you have the same method, and they can be used by the same way?

```php
//With permission slugs:
$group->assignPermissions(['permission-slug-1', 'permission-slug-2']);

//With permission ids:
$group->assignPermissions([1, 2, 3]);

//With instances of permission model:
$group->assignPermissions([Permission::find(1), Permission::find(2)]);

//With the three ways above combined:
$group->assignPermissions([1, 'permission-slug', Permission::find(1)]);
```

After add permissions to a group, you may want/need to add a user to a group.
This can be done in two different ways:

#### First way:
You can add a group to a user, and use 4 different types of parameters:
```php
//Assign a group to a user, with a group slug array:
$user->assignGroup(['group-slug-1', 'group-slug-2']);

//Assign a group to a user, with a group ids array:
$user->assignGroup([1, 2, 3]);

//Assign a group to a user, with instance of group models array:
$user->assignGroup([Group::find(1), Group::find(2)]);

//Assign group to a user, combining the three methods above:
$user->assignGroup([Group::find(1), 'group-slug-2', 3]);
```
#### Second way:
You can add a user to a group, and use 4 different types of parameters:
```php
//Assign a user to a group, with a user names slug array:
$group->assignUser(['User one', 'User two']);

//Assign a user to a group, with a user ids array:
$group->assignUser([1, 2, 3]);

//Assign a user to a group, with instance of User models array:
$group->assignUser([User::find(1), User::find(2)]);

//Assign a user to a group combining the three methods above:
$group->assignUser([User::find(1), 'User name', 3]);
```

### Revoke permissions
#### 1 - Revoke permissions from user
You can revoke a user permission using the method below:
```php
$user->revokePermissions(['permission-slug', 2, Permission::find(3)]);
```
Like the methods to add or remove a group from a user, you can use as function parameter a
array of permission ids, permission slugs, instance of permission model, or,
combine these three ways.

#### 2 - Revoke permissions from groups:
You can revoke a group permission using the method below:
```php
$group->revokePermissions(['permission-slug', 2, Permission::find(3)]);
```
Like the methods to add or remove a group from a user, you can use as function parameter a
array of permission ids, permission slugs, instance of permission model, or,
combine these three ways.

#### 3 - Revoke a group from user:
You can remove a group from the user by using one of these methods:
```php
$user->revokeGroup(['permission-slug', 2, Permission::find(3)]);
$group->removeUser(['User name', 2, User::find(3)]);
```
Like the methods to add or remove a group from a user, you can use as function parameter a
array of group/user ids, group/user slugs, instance of group/user model , or,
combine these three ways.

# Checking for permissions
### Checking if user has permission:
You can check if a user has a permission using:
```php
//With permission slugs:
$user->hasPermission('permission-slug');

//With permission ids array:
$user->hasPermission(1);

//With instance of permission model:
$user->hasPermission(Permission::find(1));
```
If the user has the permissions passed, the function return `true`. Otherwise, returns `false`;

You can also check if the user has any permission:
```php
//With permission slugs array:
$user->hasAnyPermission(['permission-slug-1', 'permission-slug-2']);

//With permission ids array:
$user->hasAnyPermission([1, 2, 3]);

//With instance of permission model array:
$user->hasAnyPermission([Permission::find(1), Permission::find(2), Permission::find(3)]);

//With the three methods above combined:
$user->hasAnyPermission([1, 'permission-slug' Permission::find(3)]);
```
If the user has any of the permissions passed, the function return `true`. Otherwise, returns `false`;

### Checking if user has permission trough group:
You can check if one user is associated with a group which has the required permission:

```php
//With permission id:
$user->hasPermissionThroughGroup(1);

//With instance of permission model:
$user->hasPermissionThroughGroup(Permission::find(1));

//With permission slug:
$user->hasPermissionThroughGroup('admin');
```
### Checking if group has permissions:
You can check if a group has a required permission with:

```php
//With permission id:
$group->hasPermission(1);

//With permission slug:
$group->hasPermission('permission-slug');

//With instance of permission model:
$group->hasPermission(Permission::find(1));
```
### Checking if a group has any permission:
In the same way as for users, you can check if a group has any of the required permissions:

```php
//With permission slugs array:
$group->hasAnyPermission(['permission-slug-1', 'permission-slug-2']);

//With permission ids array:
$group->hasAnyPermission([1, 2, 3]);

//With instance of permission model array:
$group->hasAnyPermission([Permission::find(1), Permission::find(2), Permission::find(3)]);

//With the three methods above combined:
$group->hasAnyPermission([1, 'permission-slug' Permission::find(3)]);
```

## Syncing user permissions
The user permissions can synced with this method:
```php
//With permission id array:
$user->syncPermissions([1, 2, 4]);

//With permission slugs array:
$user->syncPermissions(['permission-slug-1', 'permission-slug-2']);

//With instance of permission model arrays:
$user->syncPermissions([Permission::find(1), Permission::find(2)]);

//Combining the three ways:
$user->syncPermissions([1, 'permission-slug', Permission::find(3)]);
```

## Syncing group permissions
The groups permissions can synced with this method:
```php
//With permission id array:
$group->syncPermissions([1, 2, 4]);

//With permission slugs array:
$group->syncPermissions(['permission-slug-1', 'permission-slug-2']);

//With instance of permission model arrays:
$group->syncPermissions([Permission::find(1), Permission::find(2)]);

//Combining the three ways:
$group->syncPermissions([1, 'permission-slug', Permission::find(3)]);
```

The `UserTrait.php` trait also adds a `group` scope to the query to certain groups
or permissions:

```php
//Return only users with the group 'admin':
$users = User::group('admin')->get();
```
The `group` scope can accept a `\Junges\ACL\Http\Models\Group::class` object or an
`\Illuminate\Support\Collection` object.

The same trait also adds a scope to only get users who have a certain permission.

```php
//Return only users with the permission 'edit-post' (directly or via groups)
$users = User::permission('edit-post')->get();
```

The `permission` scope can accept a string (permission slug), a `\Junges\ACL\Http\Models\Permission::class` or an
`\Illuminate\Support\Collection` object.

# Blade and permissions
To check for permissions with this package, you can still using laravel built in `@can` blade
directive and `can()` method:

```php
@can('edit-post')
    I can edit the post
@endcan
```

```php
@if(auth()->user()->can('edit-post'))
    I can edit the post!
@endcan
```

### Using package custom blade directives
This package also adds Blade directives to verify whether
the currently logged in user has a given list of groups/permissions.

For groups:
```php
@group('admin')
    I have the admin group!
@endgroup
```
For permissions:
```php
@permission('admin')
    I have the admin permission!
@endpermission
```
You can only use blade directives with group/permission id or slug.

# Using a Middleware


If you want to use the middleware provided by this package
(`PermissionMiddleware`, `GroupMiddleware` e `PermissionOrGroupMiddleware`),
you need to add them to the `app/Http/Kernel.php` file,
inside the `routeMiddleware` array:
```php
protected $routeMiddleware = [
    'permissions' => \Junges\ACL\Middlewares\PermissionMiddleware::class,
    'groups' => \Junges\ACL\Middlewares\GroupMiddleware::class,
    'permissionOrGroup' => \Junges\ACL\Middlewares\PermissionOrGroup::class,
];
```
Then you can protect you routes using middleware rules:

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('perissions:admin');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('perissionOrGroup:admin');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('groups:admin');
```

Alternatively, you can separate multiple groups or permissions with a `|` (pipe) character:
```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('perissions:admin|manager');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('perissionOrGroup:admin|manager');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('groups:admin|manager');
```

You can protect controller similarly, by setting desired middleware in the constructor:

```php
public function __construct()
{
    $this->middleware(['groups:admin', 'permissions:edit']);
}
```
```php
public function __construct()
{
    $this->middleware('permissions:admin|manager');
}
```

The `groups` middleware will check if the current logged in user has any of the groups passed to the middleware.

The `permissions` middleware will check if the current logged in user has any of the required groups
for a route.

The `permissionOrGroup` will check if the current logged in user has any of the required permissions or
groups necessary to access a route.

In positive case, both middleware guarantee access to the route.

# Using artisan commands

You can create a group or a permission from a console with artisan commands:

```bash
php artisan group:create name slug description
```
```bash
php artisan permission:create name slug description
```

## Extending and replacing models

If you need to EXTEND the existing `Group` or `Permission` models note that:

- Your `Group` model needs to extend the `\Junges\ACL\Http\Models\Group` model
- Your `Permission` model needs to extend the `\Junges\ACL\Http\Models\Permission` model

If you need to REPLACE the existing `Group` or `Permission` models you need to keep the
following things in mind:

- Your `Group` model needs to use the `\Junges\ACL\Traits\GroupTrait` trait
- Your `Permission` model needs to implement the `\Junges\ACL\Traits\PermissionTrait` trait

In both cases, whether extending or replacing, you will need to specify your new models
in the configuration.

To do this you must update the `models.group` and `models.permission` values in the configuration file.

### Basic form templates
This package provides form to add a group or permission to the user, and permissions to groups.
Just include the view on you form:

```php
<form action="" method="">
    @include('acl::_forms.groups.group')
</form>
```
```php
<form action="" method="">
    @include('acl::_forms.users.add-group')
</form>
```
```php
<form action="" method="">
    @include('acl::_forms.users.add-permission')
</form>
```
