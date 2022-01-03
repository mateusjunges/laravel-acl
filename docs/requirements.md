---
title: Requirements
weight: 2
---

### Laravel version
This package can be used in Laravel v8 or higher

### Reserved keywords
This package uses some method names and properties in order to manage model permissions. The model in which you are adding permissions functionality
must not have a `group` or `groups` property or database column. It must also not have a `groups()` method defined.

The model should not contain a `permission` or `permissions` property, nor a `permissions()` method.

The mentioned methods/properties will interfere with properties and methods add by the `HasPermissions` trait provided by this package.

