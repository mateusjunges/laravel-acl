<?php

namespace Junges\ACL\Exceptions\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Junges\ACL\Exceptions\PermissionAlreadyExistsException;

class PermissionDoesNotExistSolution implements RunnableSolution
{
    /**
     * The slug to build the permission off of.
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
        return 'The Permission does not exist';
    }

    public function getSolutionDescription(): string
    {
        return 'Did you forget to create the permission `'.$this->slug.'` with `php artisan permission:create`?';
    }

    public function getDocumentationLinks(): array
    {
        return [];
    }

    public function getSolutionActionDescription(): string
    {
        return 'Pressing the button below will try to create the missing permission for you.';
    }

    public function getRunButtonText(): string
    {
        return 'Create Permission';
    }

    public function run(array $parameters = [])
    {
        $permissionModel = app(config('acl.models.permission'));

        $permission = $permissionModel->where('slug', $parameters['slug'])
                                        ->orWhere('name', $parameters['name'])
                                        ->first();
        if (! is_null($permission)) {
            throw PermissionAlreadyExistsException::create();
        }

        $permissionModel->create($parameters);
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
            'description' => $name.' permission',
        ];
    }
}
