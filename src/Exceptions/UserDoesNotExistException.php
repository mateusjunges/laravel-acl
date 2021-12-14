<?php

namespace Junges\ACL\Exceptions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class UserDoesNotExistException extends InvalidArgumentException
{
    public static function named($name): self
    {
        $message = trans('acl::acl.user_does_not_exist_with_name');

        return new static($message.' '.$name, Response::HTTP_BAD_REQUEST);
    }

    public static function withId($id): self
    {
        $message = trans('acl::acl.user_does_not_exist');

        return new static($message.' '.$id, Response::HTTP_BAD_REQUEST);
    }
}
