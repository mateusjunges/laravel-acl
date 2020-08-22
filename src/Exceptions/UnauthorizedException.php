<?php

namespace Junges\ACL\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    /**
     * Exception used when user does not have the required groups to access some route.
     *
     * @param array $groups
     * @return UnauthorizedException
     */
    public static function forGroups(array $groups = []): self
    {
        $message = trans('acl::acl.forGroups');

        return  new static(Response::HTTP_FORBIDDEN, $message, null, $groups);
    }

    /**
     * Exception used when user does not have the required permissions to access some route.
     *
     * @param array $permissions
     * @return UnauthorizedException
     */
    public static function forPermissions(array $permissions = []): self
    {
        $message = trans('acl::acl.forPermissions');

        return new static(Response::HTTP_FORBIDDEN, $message, null, $permissions);
    }

    /**
     * User are not logged in.
     *
     * @return UnauthorizedException
     */
    public static function notLoggedIn(): self
    {
        $message = trans('acl::acl.notLoggedIn');

        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }

    /**
     * Used to return the exception when the user doesn't have any of the required permissions.
     *
     * @return UnauthorizedException
     */
    public static function forGroupsOrPermissions(): self
    {
        $message = trans('acl::acl.forGroupsOrPermissions');

        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }
}
