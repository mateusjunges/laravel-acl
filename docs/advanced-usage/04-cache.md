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

Or using the artisan command:

```bash
php artisan acl:reset-cache
```

### Cache expiration time
The default cache expiration time is 24 hours. You may alter the expiration time in `config/acl.php` configuration file, in the `cache` array.

### Cache key
The default cache key used is `junges.acl.cache`, and you are not encouraged to change it. More likely, setting the cache prefix is better.

### Custom cache store
You can configure this package to use any cache store you have configured in laravel's cache config file `config/cache.php`. In `config/acl.php`, set the `cache.store` key to 
the name of any store you have defined.


#### Disabling cache
Setting the `cache.store` to `array` will disable caching by this package between requests, but will keep cache in memory until the current request is completed and processing, never persisting it.

