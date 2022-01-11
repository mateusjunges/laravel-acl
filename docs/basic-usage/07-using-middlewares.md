---
title: Using middlewares
weight: 7
---

If you want to check for single permissions using the laravel default's `can` method, you can use the laravel built-in `can` middleware:

```php
Route::group(['middleware' => ['can:create posts']], function () {

});
```

This package also provides you three custom middlewares:

- GroupMiddleware
- PermissionMiddleware
- PermissionOrGroupMiddleware

To use them, you must add it to your `app/Http/Kernel.php` file:

```php
protected $routeMiddleware = [
    'group' => \Junges\ACL\Middlewares\GroupMiddleware::class,
    'permission' => \Junges\ACL\Middlewares\PermissionMiddleware::class,
    'permission_or_group' => \Junges\ACL\Middlewares\PermissionOrGroupMiddleware::class
];
```

Then you can protect your routes using middleware rules:

```php
Route::group(['middleware' => ['permission:create posts']], function() {

});

Route::group(['middleware' => ['group:super admin']], function() {

});

Route::group(['middleware' => ['permission:create posts', 'group:manager']], function() {

});

Route::group(['middleware' => ['permission:create posts|edit posts']], function() {

});

Route::group(['middleware' => ['permission_or_group:manage posts']], function() {

});
```