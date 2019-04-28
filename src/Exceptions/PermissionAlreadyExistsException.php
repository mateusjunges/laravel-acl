<?php


namespace Junges\ACL\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExistsException extends InvalidArgumentException
{
    /**
     * Exception thrown when the permission already exists on database
     * @param string $permission
     * @return PermissionAlreadyExistsException
     */
    public static function create(string $permission)
    {
        $message = trans('acl::acl.permission_already_exists');
        return new static($message);
    }
}
