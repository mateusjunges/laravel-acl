---
title: Basic usage
weight: 1
---

After installing the package, you need to add the `Junges\ACL\Concerns\HasGroups` trait to your model:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Junges\ACL\Concerns\HasGroups;

class User extends Authenticatable
{
    use HasGroups;

    // ...
}
```

Laravel ACL allows models to be associates with permissions and groups. Each group may be associated with multiple permissions. A `Group` and a 
`Permission` are regular Eloquent models which require a `name` and can be created like this:

```php
use Junges\ACL\Models\Group;
use Junges\ACL\Models\Permission;

$group = Group::create(['name' => 'manager']);
$permission = Permission::create(['name' => 'add employees']);
```

A permission can be assigned to a group:

```php
$group->assignPermission($permission);
$permission->assignGroup($group);
```