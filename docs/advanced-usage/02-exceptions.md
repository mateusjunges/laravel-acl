---
title: Exceptions
weight: 2
---

If you want to override the exceptions thrown by this package, you can add them to the `app/Exceptions/Handler.php` file. For example:

```php
public function register()
{
    $this->renderable(function (\Junges\ACL\Exceptions\UnauthorizedException $exception, $request) {
        return response()->json([
            'message' => 'Authorization failed.',
            'status' => 403
        ]);
    });
}
```

You can find all exceptions added by this package [here](https://github.com/mateusjunges/laravel-acl/tree/master/src/Exceptions).