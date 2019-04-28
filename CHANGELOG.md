# Changelog

All notable changes to `mateusjunges/laravel-acl` will be documented in this file.

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
