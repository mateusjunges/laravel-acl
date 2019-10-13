<?php

namespace Junges\ACL\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Junges\ACL\Traits\UsersTrait;

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
        return 'Pressing add trait will try to add the missing trait to your users model automatically';
    }

    public function getRunButtonText(): string
    {
        return 'Add UsersTrait';
    }

    public function run(array $parameters = [])
    {
        $userClass = $parameters['class'];
        $composer = require base_path('/vendor/autoload.php');
        info($composer->getClassMap());
        
        $classes = array_filter($composer->getClassMap(), function ($class) use ($userClass) {
            if (\Illuminate\Support\Str::contains($class, 'User')) {
                return $class === $userClass;
            }
            return false;
        }, ARRAY_FILTER_USE_KEY);

        $originalFile = file_get_contents($classes[$userClass]);
        $newFile = preg_replace('/use /', 'use ' . UsersTrait::class . ";\nuse ", $originalFile, 1);
        $newFile = preg_replace("/{\n/", "{\n ".'    use ' . 'UsersTrait' . ";\n", $newFile, 1);
        
        file_put_contents($classes[$userClass], $newFile);
    }

    public function getRunParameters(): array
    {
        return [
            'class' => $this->class 
        ];
    }

}
