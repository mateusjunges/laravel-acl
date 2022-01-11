---
title: Creating a super admin
weight: 5
---

To add a super admin to your system, you should use the global `Gate::before` or `Gate::after` rules.

Then you can just use the permission-based controls in your application, without always checking for `isAdmin` or anything else everywhere


### `Gate::before`

If you want your super admin to return true for all permissions checking without assigning all permissions to that user, you should use the
laravel default `Gate::before` method.

```php
namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::before(fn ($user, $ability) => $user->hasGroup('Super Admin') ? true : null);
    }
}
```

The closure passed to `Gate::before` will be used before any policy gets called. 

The `Gate::before` method needs to return null instead of false in order to not interfere with normal policy operations.

### `Gate::after`

With `Gate::after` instead of `Gate::before`, the policies will be called first, even for superadmins.

```php
namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::after(fn ($user, $ability) => $user->hasGroup('Super Admin'));
    }
}
```
