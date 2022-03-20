<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class PermissionDoesNotExistException extends InvalidArgumentException
{
    /**
     * @param $id
     *
     * @return PermissionDoesNotExistException
     */
    public static function withId($id): self
    {
        $message = trans('acl::acl.permission_does_not_exist');

        return new static($message.' '.$id, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param $slug
     *
     * @return PermissionDoesNotExistException
     */
    public static function withSlug($slug): self
    {
        $message = trans('acl::acl.permission_does_not_exist_with_slug');

        return new static($message.' '.$slug, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return PermissionDoesNotExistException
     */
    public static function nullPermission(): self
    {
        $message = trans('acl::acl.null_model');

        return new static($message, Response::HTTP_BAD_REQUEST);
    }
}
