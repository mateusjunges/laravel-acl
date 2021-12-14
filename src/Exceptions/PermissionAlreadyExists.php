<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExists extends InvalidArgumentException
{
    public static function create(): self
    {
        $message = trans('acl::acl.permission_already_exists');

        return new static($message);
    }

    public static function withSlugAndGuard(string $slug, string $guardName): self
    {
        return new static("A permission with slug `$slug` already exists for guard `$guardName` already exists");
    }
}
