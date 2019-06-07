<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;

class GroupAlreadyExistsException extends InvalidArgumentException
{
    /**
     * Thrown when the group with the given slug already exists.
     */
    public static function create()
    {
        $message = trans('acl::acl.group_already_exists');

        throw new static($message);
    }
}
