---
title: Extending
weight: 3
---

All laravel authorization features works in models that implement the `Illuminate\Foundation\Auth\Access\Authorizable` trait.

### Extending group and permission models

If you are extending or replacing the `group` or `permission` models, you will need to specify your new models in the `config/acl.php` file.

Your new `Group` model needs to extend the `\Junges\ACL\Models\Group` model, and the new `Permission` model needs to extend the `Junges\ACL\Models\Permission` model.

