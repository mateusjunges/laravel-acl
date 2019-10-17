<?php

namespace Junges\ACL\Exceptions\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;

class GroupDoesNotExistSolution implements RunnableSolution
{
    /**
     * The slug to build the group off of.
     *
     * @var string
     */
    private $slug;

    public function __construct(string $slug = null)
    {
        $this->slug = $slug;
    }

    public function getSolutionTitle(): string
    {
        return 'The Group '.$this->slug.' does not exist';
    }

    public function getSolutionDescription(): string
    {
        return 'Did you forget to create the group `'.$this->slug.'` with `php artisan group:create`?';
    }

    public function getDocumentationLinks(): array
    {
        return [];
    }

    public function getSolutionActionDescription(): string
    {
        return 'Pressing the button below will try to create the missing group for you.';
    }

    public function getRunButtonText(): string
    {
        return 'Create Group';
    }

    public function run(array $parameters = [])
    {
        $groupModel = app(config('acl.models.group'));
        $group = $groupModel->where('slug', $parameters['slug'])
                            ->orWhere('name', $parameters['name'])
                            ->first();

        if (! is_null($group)) {
            throw GroupAlreadyExistsException::create();
        }

        $groupModel->create($parameters);
    }

    /*
     *  The array you return here will be passed to the `run` function.
     *
     *  Make sure everything you return here is serializable.
     *
     */
    public function getRunParameters(): array
    {
        $name = ucwords(str_replace('-', ' ', $this->slug));

        return [
            'name' => $name,
            'slug' => $this->slug,
            'description' => $name.' group',
        ];
    }
}
