# Changelog

All notable changes to `mateusjunges/laravel-acl` will be documented in this file.

## 1.6.1
- Changed language of the package commands to english. [#69](https://github.com/mateusjunges/laravel-acl/issues/69)

## 1.6.0
- Fix [#59](https://github.com/mateusjunges/laravel-acl/issues/59)
- Added exception that throws if you try to add a non existing permission to an user;
- Added exception that throws if you try to add a non existing group to an user;
- Added exception that throws if you try to add a non existing user to an group;
- From this version forward, the permission and groups can be removed from users without any alert 
if you add a non existing permission/group to the `revokePermissions` or `revokeGroup` methods,
 as described [here](https://github.com/mateusjunges/laravel-acl/issues/59#issuecomment-491426217);

## 1.5.2
- Fix [#61](https://github.com/mateusjunges/laravel-acl/issues/61)
- Fix [#62](https://github.com/mateusjunges/laravel-acl/issues/62), replacing `can()` with `hasPermission()` inside `UsersTrait`, on the `isAdmin()` function.

## 1.5.1
- Added constructors to `Permission` and `Group` models, to dynamically set the corresponding model tables.

## 1.5.0
- `Permission::create()` throws `\Junges\ACL\Exceptions\PermissionAlreadyExistsException` when trying to create an existing Permission;
- `Group::create()` throws `\Junges\ACL\Exceptions\GroupAlreadyExistsException` when trying to create an existing Group;

## 1.4.3
- Fix `GroupsTrait.php` issue, as described here [#56](https://github.com/mateusjunges/laravel-acl/issues/56)

## 1.4.2
- Fix `add-group.blade.php` view, as mentioned in [#54](https://github.com/mateusjunges/laravel-acl/issues/54)

## 1.4.1
- Fix `permission:show` artisan command, as mentioned in [#51](https://github.com/mateusjunges/laravel-acl/issues/51)

## 1.4.0
- Added `group` scope to the `PermissionsTrait` and `GroupsTrait`, as documented [here](https://github.com/mateusjunges/laravel-acl/tree/master#local-scopes).
- Fixed typo on ACLServiceProvider, as mentioned [here](https://github.com/mateusjunges/laravel-acl/issues/44).

## 1.3.1
- Fixed typo on README.md [#39](https://github.com/mateusjunges/laravel-acl/issues/39)
## 1.3.0
- Added new blade directives, [#29](https://github.com/mateusjunges/laravel-acl/issues/29)
- Throws exception while creating duplicate groups or permissions
- Fix `is_numeric` and `is_string` conflicts [#34](https://github.com/mateusjunges/laravel-acl/issues/34)

## 1.2.1
- Added [translation](https://github.com/mateusjunges/laravel-acl/tree/master/src/resources/lang) for exception messages

## 1.2.0
- Added `user:permissions` artisan command to show user permissions
- Added `permission:show` artisan command to show all database permissions or the
permissions for one specified group

## 1.1.1
- Fix Readme typo in middleware documentation
- Add Exception handler documentation

## 1.1.0
- Changed all tables primary key to `bigInteger` 
- Changed resources directory to /src

## 1.0.3
- Added [CHANGELOG.md](https://github.com/mateusjunges/laravel-acl/blob/master/CHANGELOG.md) file 
- Added [CONTRIBUTING.md](https://github.com/mateusjunges/laravel-acl/blob/master/CONTRIBUTING.md) file 

## 1.0.2
- Fix minor typo error in [README.md](https://github.com/mateusjunges/laravel-acl/blob/master/README.md) file 
