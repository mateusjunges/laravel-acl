<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class GroupDoesNotExistException extends InvalidArgumentException
{
    /**
     * @param $id
     *
     * @return GroupDoesNotExistException
     */
    public static function withId($id): self
    {
        $message = trans('acl::acl.group_does_not_exist');

        return new static($message.' '.$id, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param $slug
     * @return GroupDoesNotExistException
     */
    public static function withSlug($slug): self
    {
        $message = trans('acl::acl.group_does_not_exist_with_slug');

        return new static($message.' '.$slug, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return GroupDoesNotExistException
     */
    public static function nullGroup(): self
    {
        $message = trans('acl::acl.null_model');

        return new static($message, Response::HTTP_BAD_REQUEST);
    }
}
