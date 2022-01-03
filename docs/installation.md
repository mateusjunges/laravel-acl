---
title: Installation
weight: 3
---

You can install this package using `composer`:

```bash
composer require mateusjunges/laravel-acl
```

You should publish the migration and the config file using 

```php
php artisan vendor:publish --provider="Junges\ACL\Providers\ACLServiceProvider"
```

Now, run the migrations using 

```bash
php artisan migrate
```

Add the necessary traits to your model, and you are ready to go!