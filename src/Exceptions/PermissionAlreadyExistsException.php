<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExistsException extends InvalidArgumentException
{
    /**
     * Exception thrown when the permission already exists on database.
     *
     * @return PermissionAlreadyExistsException
     */
    public static function create()
    {
        $message = trans('acl::acl.permission_already_exists');

        return new static($message);
    }
}
