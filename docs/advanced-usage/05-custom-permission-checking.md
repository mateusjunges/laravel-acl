---
title: Custom permission checking
weight: 5
---

In some cases, you may want to implement custom logic for checking if the user has a permission or not instead of just checking 
the users's permissions stored in the database.

If you want to implement custom logic for checking permissions, you must set the `register_permission_check_method` to `false`in `config/acl.php`.