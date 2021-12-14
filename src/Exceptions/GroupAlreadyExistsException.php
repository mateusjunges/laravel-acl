<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;

class GroupAlreadyExistsException extends InvalidArgumentException
{
    /**
     * Thrown when the group with the given slug already exists.
     */
    public static function create(): self
    {
        $message = trans('acl::acl.group_already_exists');

        return new static($message);
    }
}
