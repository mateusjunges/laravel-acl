<?php

namespace Junges\ACL\Exceptions\Solutions\Providers;

use Facade\IgnitionContracts\HasSolutionsForThrowable;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;
use Junges\ACL\Exceptions\Solutions\PermissionDoesNotExistSolution;
use Throwable;

class PermissionDoesNotExistSolutionProvider implements HasSolutionsForThrowable
{
    /**
     * The slug to create for the permission.
     *
     * @var string
     */
    private $slug;

    /**
     * Can the exception be solved.
     *
     * @param \Throwable $throwable
     * @return bool
     */
    public function canSolve(Throwable $throwable): bool
    {
        if (! $throwable instanceof PermissionDoesNotExistException) {
            return false;
        }
        $pattern = '/'.trans('acl::acl.permission_does_not_exist_with_slug').' ([^\s]+)/m';

        if (! preg_match($pattern, $throwable->getMessage(), $matches)) {
            return false;
        }

        $this->slug = $matches[1];

        return true;
    }

    /**
     * The solutions for the missing traits.
     *
     * @param \Throwable $throwable
     * @return array
     */
    public function getSolutions(Throwable $throwable): array
    {
        return [
            new PermissionDoesNotExistSolution($this->slug),
        ];
    }
}
