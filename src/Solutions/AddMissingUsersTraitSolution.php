<?php

namespace Junges\ACL\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Junges\ACL\Solutions\Utilities\InsertMissingTrait;

class AddMissingUsersTraitSolution implements RunnableSolution
{
    /**
     * The class which the method is called from.
     * 
     * @var string $class
     */
    private $class;

    /**
     * Construct new Solution
     */
    public function __construct(string $class = null)
    {
        if ($class) {
            $this->class = explode('::', $class)[0];
        }
    }

    public function getSolutionTitle(): string
    {
        return "You forgot to use UsersTrait on `{$this->class}`";
    }

    public function getSolutionDescription(): string
    {
        return "You can add the missing trait yourself by putting the `use UsersTrait` on you `{$this->class}`, or run the below solution.";
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
        return 'Add UsersTrait';
    }

    public function run(array $parameters = [])
    {
        InsertMissingTrait::insert($parameters['class'], 'UsersTrait');
    }

    public function getRunParameters(): array
    {
        return [
            'class' => $this->class 
        ];
    }

}
