<?php

namespace Junges\ACL\Macros;

use Illuminate\Routing\Route;

/**
 * @mixin Route
 */
class WithPermission
{
    public function __invoke(): callable
    {
        return function($permissions = []) {
            if (! is_array($permissions)) {
                $permissions = [$permissions];
            }

            $permissions = implode('|', $permissions);

            $this->middleware("permission:$permissions");

            return $this;
        };
    }
}