<?php

namespace Junges\ACL\Exceptions\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Junges\ACL\Exceptions\Solutions\Utilities\InsertMissingTrait;

class AddMissingPermissionsTraitSolution implements RunnableSolution
{
    /**
     * The class which the method is called from.
     *
     * @var string
     */
    private $class;

    /**
     * Construct new Solution.
     */
    public function __construct(string $class = null)
    {
        if ($class) {
            $this->class = explode('::', $class)[0];
        }
    }

    public function getSolutionTitle(): string
    {
        return "You forgot to use PermissionsTrait on `{$this->class}`";
    }

    public function getSolutionDescription(): string
    {
        return "You can add the missing trait yourself by putting the `use PermissionsTrait` on your `{$this->class}`, or run the below solution.";
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Usage' => 'https://mateusjunges.github.io/laravel-acl/guide/usage.html#usage',
        ];
    }

    public function getSolutionActionDescription(): string
    {
        return "Pressing the button will try to add the missing trait to your `{$this->class}` model automatically";
    }

    public function getRunButtonText(): string
    {
        return 'Add PermissionsTrait';
    }

    public function run(array $parameters = [])
    {
        InsertMissingTrait::insert($parameters['class'], 'PermissionsTrait');
    }

    public function getRunParameters(): array
    {
        return [
            'class' => $this->class,
        ];
    }
}
