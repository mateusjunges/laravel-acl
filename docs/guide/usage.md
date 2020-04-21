# Usage

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
using as parameter permissions slugs, permissions ids or instance of permissions model.
Beside that, you can also combine this 3 ways, using a permission id, 
one instance of permission model and a permission slug too.

```php
//With permission slugs:
$user->assignPermissions('permission-slug-1', 'permission-slug-2');

//Using arrays:
$user->assignPermissions(['permission-slug-1', 'permission-slug-2']);

//With permission ids:
$user->assignPermissions(1, 2, 3);

//Using arrays:
$user->assignPermissions([1, 2, 3]);

//With instances of permission model:
$user->assignPermissions(Permission::find(1), Permission::find(2));

//Using arrays:
$user->assignPermissions([Permission::find(1), Permission::find(2)]);

//With the three ways above combined:
$user->assignPermissions(1, 'permission-slug', Permission::find(1));

//Using arrays:
$user->assignPermissions([1, 'permission-slug', Permission::find(1)]);
```

Like as add permissions to user, you can add permissions to groups.
To do this, you have the same method, and they can be used by the same way:

```php
//With permission slugs:
$group->assignPermissions('permission-slug-1', 'permission-slug-2');

//Using arrays:
$group->assignPermissions(['permission-slug-1', 'permission-slug-2']);

//With permission ids:
$group->assignPermissions(1, 2, 3);

//Using arrays:
$group->assignPermissions([1, 2, 3]);

//With instances of permission model:
$group->assignPermissions(Permission::find(1), Permission::find(2));

//Using arrays:
$group->assignPermissions([Permission::find(1), Permission::find(2)]);

//With the three ways above combined:
$group->assignPermissions(1, 'permission-slug', Permission::find(1));

//Using arrays:
$group->assignPermissions([1, 'permission-slug', Permission::find(1)]);
```

After add permissions to a group, you may want/need to add a user to a group.
This can be done in two different ways:

### First way:

You can add a group to a user, and use 4 different types of parameters:

```php
//Assign a group to a user, using group slugs:
$user->assignGroup('group-slug-1', 'group-slug-2');

//Using arrays: 
$user->assignGroup(['group-slug-1', 'group-slug-2']);

//Assign a group to a user, using group ids:
$user->assignGroup(1, 2, 3);

//Using arrays:
$user->assignGroup([1, 2, 3]);

//Assign a group to a user, with instance of group models:
$user->assignGroup(Group::find(1), Group::find(2));

//Using arrays:
$user->assignGroup([Group::find(1), Group::find(2)]);

//Assign group to a user, combining the three methods above:
$user->assignGroup(Group::find(1), 'group-slug-2', 3);

//Usign arrays:
$user->assignGroup([Group::find(1), 'group-slug-2', 3]);
```

### Second way:

You can add a user to a group, and use 4 different types of parameters:

```php
//Assign a user to a group, with user names:
$group->assignUser('User one', 'User two');

//Using arrays:
$group->assignUser(['User one', 'User two']);

//Assign a user to a group, user ids:
$group->assignUser(1, 2, 3);

//Using arrays:
$group->assignUser([1, 2, 3]);

//Assign a user to a group, with instance of User models:
$group->assignUser(User::find(1), User::find(2));

//Using arrays:
$group->assignUser([User::find(1), User::find(2)]);

//Assign a user to a group combining the three methods above:
$group->assignUser(User::find(1), 'User name', 3);

//Using arrays:
$group->assignUser([User::find(1), 'User name', 3]);
```

## Customize the `admin` permission

This package allows you to customize the name of your `admin` permission. 
All you have to do is publish the configuation file and change the `admin_permission` to the slug you want to use.
If this key is no present in the `config/acl.php` file, the `admin` slug will be used:

```php
'admin_permission' => 'admin',
```

## Revoke permissions

### 1 - Revoke permissions from user
You can revoke a user permission using the method below:

```php
$user->revokePermissions('permission-slug', 2, Permission::find(3));

//Or with arrays:
$user->revokePermissions(['permission-slug', 2, Permission::find(3)]);
```

Like the methods to add or remove a group from a user, you can use as function parameter a
the permission ids, permission slugs, instance of permission model, or, 
combine these three ways.

### 2 - Revoke permissions from groups:

You can revoke a group permission using the method below:

```php
$group->revokePermissions('permission-slug', 2, Permission::find(3));

//Or with arrays:
$group->revokePermissions(['permission-slug', 2, Permission::find(3)]);
```

Like the methods to add or remove a group from a user, you can use as function parameter 
the permission ids, permission slugs, instance of permission model, or, 
combine these three ways.

### 3 - Revoke a group from user:

You can remove a group from the user by using one of these methods:

```php
$user->revokeGroup('permission-slug', 2, Permission::find(3));
$group->removeUser('User name', 2, User::find(3));

//Or with arrays:
$user->revokeGroup(['permission-slug', 2, Permission::find(3)]);
$group->removeUser(['User name', 2, User::find(3)]);
```

Like the methods to add or remove a group from a user, you can use as function parameter a
 group/user ids, group/user slugs, instance of group/user model , or, 
combine these three ways.

## Checking for permissions

### Checking if user has permission:

You can check if a user has a permission using:

```php
//With permission slug:
$user->hasPermission('permission-slug');

//With permission id:
$user->hasPermission(1);

//With instance of permission model:
$user->hasPermission(Permission::find(1));
```

If the user has the permissions passed, the function return `true` . Otherwise, returns `false` .

You can also check if the user has any permission:

```php
//With permission slugs:
$user->hasAnyPermission('permission-slug-1', 'permission-slug-2');

//With permission ids:
$user->hasAnyPermission(1, 2, 3);

//With instance of permission model:
$user->hasAnyPermission(Permission::find(1), Permission::find(2), Permission::find(3));

//With the three methods above combined:
$user->hasAnyPermission(1, 'permission-slug' Permission::find(3));
```

If the user has any of the permissions passed, the function return `true` . Otherwise, returns `false` .

### Checking if user has group:

To check if some user has a specific group, you can use the `hasGroup` method. This method accept a `Group` model, 
the group id or the group slug:

```php
// With group model:
$user->hasGroup(Group::find(1));

// With group id:
$user->hasGroup(1);

// With group slug:
$user->hasGroup('some-group-slug');
```

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

### Checking if user has permission direct assigned (not trough groups): 
Sometimes you need to check if one of your users has a permission assigned directly, and not trough group.
For example: the `$user` has the permission `$permission` assigned via group, but the `$permission2` is assigned
directly. The `hasPermission` method returns `true` for both permissions. If you want to check for direct permissions,
just use the `hasDirectPermission` method:

```php
// With permission model:
$user->hasDirectPermission(Permission::find(1));

// With permission slugs:
$user->hasDirectPermission('some-permission-slug');

// With permission ids:
$user->hasDirectPermission(1);
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
//With permission slugs:
$group->hasAnyPermission('permission-slug-1', 'permission-slug-2');

//With permission ids:
$group->hasAnyPermission(1, 2, 3);

//With instance of permission model:
$group->hasAnyPermission(Permission::find(1), Permission::find(2), Permission::find(3));

//With the three methods above combined:
$group->hasAnyPermission(1, 'permission-slug' Permission::find(3));
```

## Checking for permissions using wildcards

Sometimes, you want to know if the logged in user has any permission related to users, like
`*.users` . It can easily be done with the `ACLWildcardsTrait` .

Add the `ACLWildcardsTrait` to your `user` model:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Junges\ACL\Traits\UsersTrait;
use Junges\ACL\Traits\ACLWildcardsTrait;

class User extends Authenticatable
{
    use UsersTrait;
    use ACLWildcardsTrait;

    //
}
```

Then, you can check for wildcard permissions using the `hasPermissionWithWildcard` method:

```php
$user->hasPermissionWithWildcards('users.*');
```

You can also use this trait to check for group permissions using wildcards.
The `ACLWildcardsTrait` is used by the Group model by default:

```php
$group->hasPermissionWithWildcards('users.*');
```

## Syncing user permissions

The user permissions can synced with this method:

```php
//With permission id:
$user->syncPermissions(1, 2, 4);

//Using array:
$user->syncPermissions([1, 2, 4]);

//With permission slugs:
$user->syncPermissions('permission-slug-1', 'permission-slug-2');

//Using arrays:
$user->syncPermissions(['permission-slug-1', 'permission-slug-2']);

//With instance of permission model:
$user->syncPermissions(Permission::find(1), Permission::find(2));

//Using arrays:
$user->syncPermissions([Permission::find(1), Permission::find(2)]);

//Combining the three ways:
$user->syncPermissions(1, 'permission-slug', Permission::find(3));

//Using arrays:
$user->syncPermissions([1, 'permission-slug', Permission::find(3)]);
```

## Syncing user groups

The user groups can synced using this method:

```php
//With group ids:
$user->syncGroups(1, 2, 3, 4);

//Using array:
$user->syncGroups([1, 2, 3, 4]);

//Using group slugs:
$user->syncGroups('group-1', 'group-2');

//Using arrays of group slugs:
$user->syncGroups(['group-1', 'group-2']);

//Using group models:
$user->syncGroups(Group::find(1), Group::find(2));

//Using array of group models:
$user->syncGroups([Group::find(1), Group::find(2)]);

//Combining the three ways:
$user->syncGroups(1, 'group-2', Group::find(3));

//Or with arrays:
$user->syncGroups([1, 'group-2', Group::find(3)]);
```

## Syncing group permissions

The groups permissions can synced with this method:

```php
//With permission id:
$group->syncPermissions(1, 2, 4);

//Usign arrays:
$group->syncPermissions([1, 2, 4]);

//With permission slugs:
$group->syncPermissions('permission-slug-1', 'permission-slug-2');

//Using arrays:
$group->syncPermissions(['permission-slug-1', 'permission-slug-2']);

//With instance of permission model:
$group->syncPermissions(Permission::find(1), Permission::find(2));

//Using arrays:
$group->syncPermissions([Permission::find(1), Permission::find(2)]);

//Combining the three ways:
$group->syncPermissions(1, 'permission-slug', Permission::find(3));

//Using arrays:
$group->syncPermissions([1, 'permission-slug', Permission::find(3)]);
```

## Some "shortcuts"

The version `1.7.0` of this package provides some new methods to handle with groups and permissions.
From now, you can use the `assignAllPermissions` method to assign all your system permissions to your users, 
instead of doing this manually.
Similarly, the `revokeAllPermissions` , `assignAllGroups` and `revokeAllGroups` have similar functions.
Here is some example:

```php
//If you want to remove all user permissions:
$user->revokeAllPermissions();

//If you want to grant all permissions for some user:
$user->assignAllPermissions();

//Remove all user groups:
$user->revokeAllGroups();

//Add all groups to the user:
$user->assignAllGroups();
```    

The `GroupsTrait` has some new methods as well:

```php
//To remove all group permissions:
$group->revokeAllPermissions();

//To add all permissions for some group:
$group->assignAllPermissions();

//Attach all users to a group:
$group->attachAllUsers();

//Detach all users from a group:
$group->detachAllUsers();
```

## Local scopes

### The `group` scope
The `UserTrait.php` trait also adds a `group` scope to the query to certain groups
or permissions:

```php
//Return only users with the group 'admin':
$users = User::group('admin')->get();
```

The `group` scope can accept a `\Junges\ACL\Http\Models\Group::class` object or an
`\Illuminate\Support\Collection` object.

### The `permission` scope

The same trait also adds a scope to only get users who have a certain permission.

```php
//Return only users with the permission 'edit-post' (directly or via groups)
$users = User::permission('edit-post')->get();
```

The `permission` scope can accept a string (permission slug), a `\Junges\ACL\Http\Models\Permission::class` or an
`\Illuminate\Support\Collection` object.

### The `user` scope (on `\Junges\ACL\Traits\PermissionsTrait` )

The `PermissionsTrait` adds a `user` scope to the query to certain users.
With this scope, only permissions granted to the given user will be returned.

```php
//Return only permissions granted to the 'Test' user:
$permissions = Permission::user('Test')->get();
```

### The `user` scope (on `\Junges\ACL\Traits\GroupsTrait` )

The `GroupsTrait` adds a `user` scope to the query to certain users.
With this scope, only groups granted to the given user will be returned.

```php
//Returns only groups granted to the 'Test' named user:
$groups = Group::user('Test')->get();
```

The `user` scope can accept a string (user name, username, user email), a `App\User::class` instance or the user `id` .


## Retrieving user permissions

If you want to get all user permissions (directly assigned), you can use the  `permissions` method:
```php
$permissions = $user->permissions()->get();
```

To also get permission assigned to the user via a group, you can use the `getAllPermissions` method:
```php
$user->getAllPermissions();
```

## Retrieving user groups

To get all user groups you just need to use the `groups` method:
```php
$groups = $user->groups()->get();
```

The `user` scope can accept a string (user name, username, user email), a `App\User::class` instance or the user `id` .

## Blade and permissions

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

## Using package custom blade directives

This package also adds Blade directives to verify whether
the currently logged in user has a given list of groups/permissions.

The custom blade directives provided by this package are:

* @group
* @elsegroup
* @permission
* @elsepermission
* @allpermissions
* @allgroups
* @anypermission
* @anygroup

For groups:

```php
@group('admin')
    I have the admin group!
@elsegroup('editor')
    I have the editor group, but not the admin!
@endgroup
```

For permissions:

```php
@permission('admin')
    I have the admin permission!
@elsepermission('writer')
    I have the writer permission, but not the admin!
@endpermission
```

Check for all permissions:

```php
@allpermissions('permission-1', 'permission-2')
    I have permission 1 and permission 2!
@endallpermissions
```

Check for any permission:

```php
@anypermission('permission-1', 'permission-2')
    I have at least one of these permissions!
@endanypermission
```

Check for all groups:

```php
@allgroups('group-1', 'group-2')
    I have group 1 and group 2!
@endallgroups
```

Check for any group:

```php
@anygroup('group-1', 'group-2')
    I have at least one of these groups!
@endanygroup
```

<b>NOTE</b>: You can only use custom blade directives with group/permission id or slug.

# Using a Middleware

If you want to use the middleware provided by this package
( `PermissionMiddleware` , `GroupMiddleware` , `HierarchicalPermissions` e `PermissionOrGroupMiddleware` ), 
you need to add them to the `app/Http/Kernel.php` file, 
inside the `routeMiddleware` array:

```php
protected $routeMiddleware = [
    'permissions' => \Junges\ACL\Middlewares\PermissionMiddleware::class,
    'groups' => \Junges\ACL\Middlewares\GroupMiddleware::class,
    'permissionOrGroup' => \Junges\ACL\Middlewares\PermissionOrGroupMiddleware::class,
    'hierarchical_permissions' => \Junges\ACL\Middlewares\HierarchicalPermissionsMiddleware::class
];
```

Then you can protect you routes using middleware rules:

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('permissions:admin');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('permissionOrGroup:admin');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('groups:admin');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('hierarchical_permissions:user.auth.admin');
```

Alternatively, you can separate multiple groups or permissions with a `|` (pipe) character:

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('permissions:admin|manager');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('permissionOrGroup:admin|manager');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('groups:admin|manager');
```

```php
Route::get('/', function(){
    echo "Middlewares working!";
})->middleware('hierarchical_permissions:user.auth.admin|user.manager.user.admin');
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

```php
public function __construct()
{
    $this->middleware('hierarchical_permissions:user.auth.admin|user.manager.user.admin');
}
```

The `groups` middleware will check if the current logged in user has any of the groups passed to the middleware.

The `permissions` middleware will check if the current logged in user has any of the required groups
for a route.

The `permissionOrGroup` will check if the current logged in user has any of the required permissions or
groups necessary to access a route.

The `hierarchical_permissions` middleware will check if the current logged in user has any of the "sub-permissions" 
in the passed string. The `user.manager.user.admin` matches with all the following:

```text
user
user.manager
user.manager.user
user.manager.user.admin
```

If the user has any of the above permissions, the access is granted.

In positive case, both middleware guarantee access to the route.

# Handling group and permission exceptions

If you want to override the default `403` response, you can catch the `Unauthorized` exception using the 
laravel exception handler:

```php
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Junges\ACL\Exceptions\UnauthorizedException) {
            // Your code here
        }
    
        return parent::render($request, $exception);
    }
```

When trying to create an existing Permission, the `Junges\ACL\Exceptions\PermissionAlreadyExistsExeption` will be throw.
You can catch the exception using default laravel handler:

```php
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Junges\ACL\Exceptions\PermissionAlreadyExistsException) {
            // Your code here
        }
    
        return parent::render($request, $exception);
    }
```

The same is valid for the `Junges\ACL\Exceptions\PermissionAlreadyExistsException` :

```php
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Junges\ACL\Exceptions\GroupAlreadyExistsException) {
        
        }
    
        return parent::render($request, $exception);
    }
```

The same is valid for `PermissionDoesNotExistException` , `GroupDoesNotExistException` and `UserDoesNotExistException.` 

# Using artisan commands

You can create a group or a permission from a console with artisan commands:

``` bash
php artisan group:create name slug description
```

``` bash
php artisan permission:create name slug description
```

You can also use artisan commands to display user and groups permissions:

The following command display all permissions stored on the database:

``` bash
php artisan permission:show
```

The above command with `--group` option display all permissions of the specified group.
You can use the `group slug` or the `group id` as option value, just like this:

``` bash
php artisan permission:show --group 1
```

Or

``` bash
php artisan permission:show --group admin
```

Likewise, you can display user permissions, using de `user:permissions` artisan command, 
with the `user id` , the user's `username` , the user `email` or the user `Name` as argument:

With the username:

``` bash
php artisan user:permissions username
```

With the user id:

``` bash
php artisan user:permissions 1
```

With user Name:

``` bash
php artisan user:permissions "User Name"
```

and with the user's email:

``` bash
php artisan user:permissions "email@domain.com"
```

# Extending and replacing models

If you need to EXTEND the existing `Group` or `Permission` models note that:

* Your `Group` model needs to extend the `\Junges\ACL\Http\Models\Group` model
* Your `Permission` model needs to extend the `\Junges\ACL\Http\Models\Permission` model

If you need to REPLACE the existing `Group` or `Permission` models you need to keep the
following things in mind:

* Your `Group` model needs to use the `\Junges\ACL\Traits\GroupTrait` trait
* Your `Permission` model needs to implement the `\Junges\ACL\Traits\PermissionTrait` trait

In both cases, whether extending or replacing, you will need to specify your new models
in the configuration.

To do this you must update the `models.group` and `models.permission` values in the configuration file.

# Route model key
If you would like model binding to use a database column other than id when
retrieving a given model class, you may override the getRouteKeyName method
on the Eloquent model with yours. The default key used for route model binding
in this package is the `slug` database column. You can modify it by changing the
`route_model_binding_keys` configuration, in `config/acl.php` file:

```php
'route_model_binding_keys' => [
    'group_model' => 'slug',
    'permission_model' => 'slug'
],
```

# Basic form templates

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

# Translations

This package also provides translations for some messages. To use them is easy:

* Change your `config/app.php` file locale for your corresponding locale, like `en` or `pt-br` .
* Publish the translation files with 

``` bash
php artisan vendor:publish --provider="Junges\ACL\ACLServiceProvider" --tag="acl-translations"
 ```

# Ignition Solutions

This package ships with a lot of useful [Ignition Solutions](https://flareapp.io/docs/solutions/introduction) out of the box. To use it, you just need to change the `offer_solutions` flag, within the `config/acl.php` file to `true`.

After changing the `offer_solutions` flag to `true`, you need to install the `facade/ignition` package as a dev dependency:

```sh
composer require facade/ignition --dev
```

> Remember to set the `APP_DEBUG` environment variable to `false`, when in production environment.

