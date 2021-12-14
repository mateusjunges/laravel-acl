<?php

namespace Junges\ACL\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    public static function forGroups(array $groups = []): self
    {
        $message = trans('acl::acl.forGroups');

        return  new static(Response::HTTP_FORBIDDEN, $message, null, $groups);
    }

    public static function forPermissions(array $permissions = []): self
    {
        $message = trans('acl::acl.forPermissions');

        return new static(Response::HTTP_FORBIDDEN, $message, null, $permissions);
    }

    public static function notLoggedIn(): self
    {
        $message = trans('acl::acl.notLoggedIn');

        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }

    public static function forGroupsOrPermissions(): self
    {
        $message = trans('acl::acl.forGroupsOrPermissions');

        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }
}
