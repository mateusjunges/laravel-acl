# Changelog

All notable changes to `mateusjunges/laravel-acl` will be documented in this file.

# 2.5.1
- Fix user model namespace for laravel v8.x

# 2.5.0
- Add support for Laravel v8.x
- Drop suppport for PHP v7.2 and lower

# 2.4.7
- Permission and group middleware now trhows UnauthorizedException with denied permissions and groups in exception headers (#175)

# 2.4.6
- Merge some dependabot dependencies PR
- Fixes #185

# 2.4.5
- Add support for spanish translations

# 2.4.4
- Makes the `admin` permission configurable. Now you can change the slug used as `admin` permission by this package.
Check [#174](https://github.com/mateusjunges/laravel-acl/pull/174)

# 2.4.3
- Add support for route model key bindings (#170)

# 2.4.2
- Change test suite to run on GitHub Actions instead of Travis CI.

# 2.4.1
- Fixed `facade/ignition` dependency for laravel 7.x

# 2.4.0
- Changed public method `getCorrectParameter` to `private` on `GroupsTrait` and `UsersTrait`
- Improved documentations
- Drop support for Laravel v5.6
- Drop support for Laravel v5.7
- Add support for Laravel v7.x

# 2.3.0
- Added `syncGroups()` method on users trait. [#155](https://github.com/mateusjunges/laravel-acl/issues/155)

## 2.2.1
- Fix [#143](https://github.com/mateusjunges/laravel-acl/issues/143)

## 2.2.0
- Add Ignition Solutions for Laravel ACL exceptions [#141](https://github.com/mateusjunges/laravel-acl/pull/141)

## 2.1.1
- Fixed bug with the `group` scope. See [#133](https://github.com/mateusjunges/laravel-acl/issues/133)

## 2.1.0
- Update dependencies for Laravel 6
- Drop support for Laravel 5.5 and older, and PHP 7.1 and older. (They can use v2.0 of this package until they upgrade.)
- Version 2.1.0 and greater of this package require PHP 7.2 and higher.

## 2.0.3
- Fix composer.json dependencies for laravel 6.0

## 2.0.2
#### In [UsersTrait.php](https://github.com/jungessolutions/laravel-acl/blob/master/src/Traits/UsersTrait.php):
- Add option to use array as parameter for `syncPermissions()` method;
- Add option to use array as parameter for `assignPermissions()` method;
- Add option to use array as parameter for `assignGroup()` method;
- Add option to use array as parameter for `revokePermissions()` method;
- Add option to use array as parameter for `assignGroup()` method;
- Add option to use array as parameter for `revokeGroup()` method;

#### In [GroupsTrait.php](https://github.com/mateusjunges/laravel-acl/blob/master/src/Traits/GroupsTrait.php):
- Add option to use array as parameter for `syncPermissions()` method;
- Add option to use array as parameter for `assignPermissions()` method;
- Add option to use array as parameter for `revokePermissions()` method;
- Add option to use array as parameter for `assignUser()` method;
- Add option to use array as parameter for `removeUser()` method;

## 2.0.1
- Update composer.json for laravel 6.0

## 2.0.0
#### In [UsersTrait.php](https://github.com/jungessolutions/laravel-acl/blob/master/src/Traits/UsersTrait.php):
- `hasAnyPermissions()`, `assignPermissions()`, `syncPermissions()`, `revokePermissions()`,
`assignGroup()`, `revokeGroup`, `hasAnyGroup()`, `hasAllGroups()` and `hasAllPermissions()`
methods now works with non array params. You can [read the docs here](https://github.com/jungessolutions/laravel-acl#checking-for-permissions);

- Added eager loading for permissions checks.

#### In [GroupsTrait.php](https://github.com/mateusjunges/laravel-acl/blob/master/src/Traits/GroupsTrait.php):
- `assignPermissions()`, `syncPermissions()`, `revokePermissions()`,
`assignUser()`, `removeUser()`, `hasAnyPermissions()` and `hasAllPermissions()`
methods now works with non array params. You can [read the docs here](https://github.com/jungessolutions/laravel-acl#checking-for-permissions);  

- Added eager loading for groups permissions checks.


#### [Blade Directives](https://github.com/jungessolutions/laravel-acl#using-package-custom-blade-directives):
- Now all blade directives does not need an array as parameter.
You still able to check for permissions using mixed parameters, like permission id, permission slug, etc.

#### Tests
- Added a test class for each trait method.
- Fix middleware tests.
- Added Exception tests.

#### Fixed:
- Fixes [#112](https://github.com/jungessolutions/laravel-acl/issues/112)

## 1.8.1
- Add `->unique()` for permission slugs (fix #109)

## 1.8.0
- Check for permissions using wildcard (fix [#77](https://github.com/jungessolutions/laravel-acl/issues/77))
- Hierarchical permissions middleware
- Added an artisan command to install the package
- Update package docs

## 1.7.5
- Fix [#93](https://github.com/mateusjunges/laravel-acl/issues/93)
- Add database connection check before register gates

## 1.7.4
- Add package tests
- Continuous integration with TravisCI

## 1.7.3
- Applies code style fixes from an analysis carried out by [StyleCI](https://styleci.io/).

## 1.7.2
- Add a way to customize package migrations.
- Fix [#75](https://github.com/mateusjunges/laravel-acl/issues/75)

## 1.7.1
- Removed all versions below 1.5.2 because of a bug in the `isAdmin` function, which caused infinite looping. 
If you use a version below 1.5.2 of this package, please upgrade as soon as possible.

## 1.7.0
### Attention:
- The `description` field, in `permissions` and `groups` tables provided by this package, from this version above, are **optional**.

### Groups Trait changes:
- Added `revokeAllPermissions()` method
- Added `assignAllPermissions()` method
- Added `attachAllUsers()` method
- Added `dettachAllUsers()` method

### Users Trait changes:
- Added `assignAllPermissions()` method
- Added `revokeAllPermissions()` method
- Added `revokeAllGroups()` method
- Added `assignAllGroups()` method


### General changes:
- Added package database [entity relationship model](https://github.com/mateusjunges/laravel-acl/blob/masterdocs/database-model.png)
- Updated README.md to version 1.7.0

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
