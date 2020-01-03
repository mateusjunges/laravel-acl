<?php

namespace Junges\ACL\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class PermissionDoesNotExistException extends InvalidArgumentException implements ProvidesSolution
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

    /**
     * Offers a text based solution for the permissions.
     *
     * @return \Facade\IgnitionContracts\Solution
     */
    public function getSolution(): Solution
    {
        return BaseSolution::create('Did you forget to create the permission?')
            ->setSolutionDescription('You can run `php artisan permission:create` with the name, slug and description in that order.')
            ->setDocumentationLinks([
                'Usage' => 'https://mateusjunges.github.io/laravel-acl/guide/usage.html#using-artisan-commands',
            ]);
    }
}
