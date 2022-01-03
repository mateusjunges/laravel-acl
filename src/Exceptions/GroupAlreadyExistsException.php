<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;

class GroupAlreadyExistsException extends InvalidArgumentException
{
    /**
     * Thrown when the group with the given slug already exists.
     */
    public static function create(string $name, string $guard): self
    {
        return new static("A group with name `$name` already exists for guard `$guard`");
    }
}
