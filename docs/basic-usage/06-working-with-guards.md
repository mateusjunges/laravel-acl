---
title: Working with guards
weight: 6
---

When you are using custom guards in your application, they act like namespaces for your permissions and groups. Every guard has its own permissions that can be assigned to 
their user model.  The downside of this is that when working with multiple guards, because this package requires you to register a permission name for each
guard you want to authenticate with, you would have to define the same permission multiple times for each guard that you are using. 

### Creating permissions with multiple guards
When you create a new permission or group, the **first** defined guard in `config/auth.php` on `guards` config array will be used.

To check if a user has permission for a specific guard, you can pass the guard as second parameter to the `hasPermission` method:

```php
$user->hasPermission('create posts', 'api');
```

When determining if a given group or permission is valid for a given model, it checks against the first matching guard in this order:

- `guardName()` method if it exists on the model;
- `$guard_name` property if it exists on the model;
- The first defined guard/provider combination in `config/auth.php` in `guards` key that matches the logged-in user's guard;
- The `config/auth.php` on `defaults.guard` config;