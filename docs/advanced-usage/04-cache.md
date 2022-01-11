---
title: Cache
weight: 4
---

All groups and permissions are cached to speed up your application performance.

When you use the built-in methods for manipulating groups and permissions, the cache is automatically reset for you, and relations are automatically reloaded for the current model record.
But, if you alter permissions or groups directly in the database instead of calling the built-in methods, you will not see the change reflected in the application unless you manually reset the cache. 
Also, because  the `Group` and `Permission` models implements the `RefreshesPermissionCache` trait, creating and deleting groups and permissions will automatically
clear the cache. 

### Manually resetting the cache

To reset the cache manually, you can use the `forgetCachedPermissions` method:

```php
app(\Junges\ACL\AclRegistrar::class)->forgetCachedPermissions();
```