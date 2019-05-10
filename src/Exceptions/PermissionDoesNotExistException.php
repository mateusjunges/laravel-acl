<?php


namespace Junges\ACL\Exceptions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class PermissionDoesNotExistException extends InvalidArgumentException
{
    /**
     *
     * @param $id
     * @return PermissionDoesNotExistException
     */
    public static function withId($id) : self
    {
        $message = trans("acl::acl.permission_does_not_exist");
        return new static($message." ".$id, Response::HTTP_BAD_REQUEST);
    }
}
