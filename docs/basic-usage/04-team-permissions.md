---
title: Team permissions
weight: 4
---

To enable team permissions, you MUST change the `teams` key to `true` in `config/acl.php`:

```php
'teams' => true,
```

To use custom foreign key name for teams you must change the `team_foreign_key` in the same configuration file:

```php
'team_foreign_key => 'custom_fk_name',
```

To start using teams, you must implement a way to select the user team on authentication. Then, you can set the global `team_id` from anywhere,
but I recommend you to create a middleware:

```php
namespace App\Http\Middleware;

use Closure;

class TeamsPermissionMiddleware
{    
    public function handle($request, Closure $next){
        if(! empty(auth()->user())){
            setPermissionsTeamId('team id'); // You should get the team id from where you set on authentication.
        }
  
        return $next($request);
    }
}
```

### Team groups and permissions

The group and permissions assignment process for teams are the same as for users, but it uses the global `team_id` set on login.