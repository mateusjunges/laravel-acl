<?php

namespace Junges\ACL\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    protected array $requiredGroups = [];
    protected array $requiredPermissions = [];

    public static function forGroups(array $groups = []): self
    {
        $message = 'User does not have the right roles.';

        if (config('acl.display_permission_in_exception')) {
            $groupsStr = implode(', ', $groups);
            $message = 'User does not have the right roles. Necessary roles are '.$groupsStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredGroups = $groups;

        return $exception;
    }

    public static function forPermissions(array $permissions = []): self
    {
        $message = 'User does not have the right permissions.';

        if (config('permission.display_permission_in_exception')) {
            $permissionsStr = implode(', ', $permissions);
            $message = 'User does not have the right permissions. Necessary permissions are '.$permissionsStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $permissions;

        return $exception;
    }

    public static function notLoggedIn(): self
    {
        $message = trans('acl::acl.notLoggedIn');

        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }

    public static function forGroupsOrPermissions(array $groupsOrPermissions = []): self
    {
        $message = 'User does not have any of the necessary access rights.';

        if (config('acl.display_permission_in_exception') && config('acl.display_role_in_exception')) {
            $permStr = implode(', ', $groupsOrPermissions);
            $message = 'User does not have the right permissions. Necessary permissions are '.$permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $groupsOrPermissions;

        return $exception;
    }

    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }

    public function getRequiredGroups(): array
    {
        return $this->requiredGroups;
    }
}
