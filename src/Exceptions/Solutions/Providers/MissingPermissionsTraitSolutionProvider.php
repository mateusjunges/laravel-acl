<?php

namespace Junges\ACL\Exceptions\Solutions\Providers;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\HasSolutionsForThrowable;
use Junges\ACL\Exceptions\Solutions\AddMissingPermissionsTraitSolution;
use Junges\ACL\Traits\PermissionsTrait;
use ReflectionClass;
use Throwable;

class MissingPermissionsTraitSolutionProvider implements HasSolutionsForThrowable
{
    /**
     * The class method is called on.
     *
     * @var string
     */
    private $class;

    /**
     * Can the exception be solved.
     *
     * @param \Throwable $throwable
     * @return bool
     */
    public function canSolve(Throwable $throwable): bool
    {
        $pattern = '/Call to undefined method ([^\s]+)/m';

        if (! preg_match($pattern, $throwable->getMessage(), $matches)) {
            return false;
        }
        $class = $matches[1];

        $this->class = $class;
        $method = explode('::', $class) ?? [];
        $method = explode(' ', end($method))[0] ?? '';
        $method = str_replace('()', '', $method);

        $reflectedClass = new ReflectionClass(PermissionsTrait::class);

        return $reflectedClass->hasMethod($method) || $reflectedClass->hasMethod('scope'.ucfirst($method));
    }

    /**
     * The solutions for the missing traits.
     *
     * @param \Throwable $throwable
     * @return array
     */
    public function getSolutions(Throwable $throwable): array
    {
        $model = explode('::', $this->class)[0];

        return [
            new AddMissingPermissionsTraitSolution($this->class),
            BaseSolution::create('The PermissionsTrait is missing.')
                ->setSolutionDescription("You have to add the `PermissionsTrait` trait to your `{$model}` model to be able to access the acl methods")
                ->setDocumentationLinks([
                    'Usage' => 'https://mateusjunges.github.io/laravel-acl/guide/usage.html#usage',
                ]),
        ];
    }
}
