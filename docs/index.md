# Laravel ACL

This package allows you to manage user permissions and groups in a database.

* [Installation](installation.md)
    * [Using install command](installation.md#install-using-aclinstall-command)
    * [Step by step installation](installation.md#step-by-step-installation)
* [Usage](#usage.md)
    * [Check for permissions](usage.md#checking-for-permissions)
    * [Check for permissions using wildcards](usage.md#checking-for-permissions-using-wildcards)
    * [Syncing user permissions](usage.md#syncing-user-permissions)
    * [Syncing group permissions](usage.md#syncing-group-permissions)
    * [Local scopes](usage.md#local-scopes)
        * [The group scope](usage.md#the-group-scope)
        * [The permission scope](usage.md#the-permission-scope)
        * [The user scope on PermissionsTrait](usage.md#the-user-scope-on-jungesacltraitspermissionstrait)
        * [The user scope on GroupsTrait](usage.md#the-user-scope-on-jungesacltraitsgroupstrait)
    * [Blade and permissions](usage.md#blade-and-permissions)
        * [Using package custom blade directives](usage.md#using-package-custom-blade-directives)
    * [Using a middleware](usage.md#using-a-middleware)
    * [Handling group and permission exceptions](usage.md#handling-group-and-permission-exceptions)
    * [Using artisan commands](usage.md#using-artisan-commands)
    * [Extending and replacing models](usage.md#extending-and-replacing-models)
    * [Using translations](usage.md#translations)
    * [Entity relationship model](usage.md#entity-relationship-model)
* [Tests](#tests)
* [Changelog](#changelog)
* [Credits](#credits)
* [License](#license)


## Tests

Run `composer test` to test this package.
 
## Changelog

Please see [changelog](https://github.com/mateusjunges/laravel-acl/blob/master/CHANGELOG.md) for more information about the changes on this package.

## Credits

- [The Web Tier](https://thewebtier.com/laravel/understanding-roles-permissions-laravel/)
- [All Contributors](https://github.com/mateusjunges/laravel-acl/graphs/contributors)

## License

The MIT License. Please see the [License File](https://github.com/mateusjunges/laravel-acl/blob/master/LICENSE) for more information.
