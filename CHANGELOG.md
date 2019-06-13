# Changelog

All notable changes to `mateusjunges/laravel-acl` will be documented in this file.

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
