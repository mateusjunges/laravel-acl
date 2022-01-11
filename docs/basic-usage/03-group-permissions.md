---
title: Group permissions
weight: 3
---

### Assigning permissions to groups:
You can add permissions to groups using the `assignPermission` method:

```php
$group->assignPermission('create posts');
```

To remove group permissions, use the `revokePermission` method:

```php
$group->revokePermission('create posts');
```

### Assigning groups to users

To assign a user to a given group, you can use two methods:

```php
$group->assignUser($user);

// Or:

$user->assignGroup($group);
```

To remove a group from a user, use the `revokeGroup` method:

```php
$user->revokeGroup(Group::find(1));
$user->revokeGroup('test group');
$user->revokeGroup(1);
```

### Check if user has groups

You can check if user has a given group, use the `hasGroup` method:

```php
$user->hasGroup('test-group');
$user->hasGroup(Group::find(1));
$user->hasGroup(1);
```

Like permissions, you can check if users has any of a given array of groups:

```php
$user->hasAnyGroup(['group one', 'group two']);
```

Or, if users has all groups:

```php
$user->hasAllGroups(['group one', 'group two']);
```
