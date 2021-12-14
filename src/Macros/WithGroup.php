<?php

namespace Junges\ACL\Macros;

use Illuminate\Routing\Route;

/**
 * @mixin Route
 */
class WithGroup
{
    public function __invoke(): callable
    {
        return function($groups = []) {
            if (! is_array($groups)) {
                $groups = [$groups];
            }

            $groups = implode('|', $groups);

            $this->middleware("groups:$groups");

            return $this;
        };
    }
}