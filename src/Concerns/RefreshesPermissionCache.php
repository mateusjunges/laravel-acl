<?php

namespace Junges\ACL\Concerns;

use Illuminate\Database\Eloquent\Model;
use Junges\ACL\AclRegistrar;

/**
 * @mixin Model
 */
trait RefreshesPermissionCache
{
    public static function bootRefreshesPermissionCache()
    {
        static::saved(function () {
            app(AclRegistrar::class)->forgetCachedPermissions();
        });

        static::deleted(function () {
            app(AclRegistrar::class)->forgetCachedPermissions();
        });
    }
}