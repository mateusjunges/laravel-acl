<?php

namespace Junges\ACL\Exceptions\Solutions\Providers;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\HasSolutionsForThrowable;
use Junges\ACL\Exceptions\Solutions\AddMissingGroupsTraitSolution;
use Junges\ACL\Traits\GroupsTrait;
use ReflectionClass;
use Throwable;

class MissingGroupsTraitSolutionProvider implements HasSolutionsForThrowable
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

        $reflectedClass = new ReflectionClass(GroupsTrait::class);

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
            new AddMissingGroupsTraitSolution($this->class),
            BaseSolution::create('The GroupsTrait is missing.')
                ->setSolutionDescription("You have to add the `GroupsTrait` trait to your `{$model}` model to be able to access the acl methods")
                ->setDocumentationLinks([
                    'Usage' => 'https://mateusjunges.github.io/laravel-acl/guide/usage.html#usage',
                ]),
        ];
    }
}
