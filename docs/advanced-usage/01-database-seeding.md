---
title: Database seeding
weight: 1
---

To avoid cache conflict errors, you must flush the cache of this package before seeding.

```php
app(\Junges\ACL\AclRegistrar::class)->forgetCachedPermissions();
```

