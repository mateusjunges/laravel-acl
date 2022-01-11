---
title: Model direct permissions
weight: 2
---

Users who have permissions assigned to them via the `model_has_permissions` table are called users with "direct permissions".

You can assign a permission to a user using the `assignPermission` method:

```php
$user->assignPermission('update posts');
```

You can also give multiple permissions at once:

```php
$user->assignPermission('update posts', 'create posts');

// Or via array:
$user->assignPermission(['update posts', 'create posts']);
```

### Revoking direct permissions
Once a user has a permission, you can revoke that permission using the `revokePermission` method:

```php
$user->revokePermission('update posts');
```

### Syncing permissions
If you want to revoke all permissions and assign new ones, you can use the `syncPermissions` method:

```php
$user->syncPermissions(['create posts', 'delete posts']);
```

### Check if a model has permission
To check if a user has a given permission, you can use the `hasPermission` method.

```php
$user->hasPermission('create posts');
```

You can also pass an integer representing the permission id:

```php
$user->hasPermission(1);
$user->hasPermission($permission->id);
```

Or the permission model:

```php
$user->hasPermission(Permission::find(1));
```

To verify if a user has any of an array of permissions, use the `hasAnyPermission` method:

```php
$user->hasAnyPermission(['edit posts', 'create posts', 'delete posts']);
```

There is also a method to check if a user has all permissions of a given array:

```php
$user->hasAllPermissions(['edit posts', 'create posts', 'delete posts']);
```

Beside the method described above, you can use the laravel `can` method, since permissions are registered using the
`Illuminate\Auth\Access\Gate` class for the default guard:

```php
$user->can('create posts');
```