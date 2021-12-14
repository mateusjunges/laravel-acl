<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExistsException extends InvalidArgumentException
{
    public static function create(): self
    {
        $message = trans('acl::acl.permission_already_exists');

        return new static($message);
    }
}
