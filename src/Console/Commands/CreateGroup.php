<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;

class CreateGroup extends Command
{
    protected $signature = 'group:create {name} {slug} {description}';

    protected $description = 'Create a new group on groups table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $groupModel = app(config('acl.models.group'));

        $group = $groupModel->where('slug', $this->argument('slug'))
            ->orWhere('name', $this->argument('name'))
            ->first();

        if (! is_null($group)) {
            throw GroupAlreadyExistsException::create();
        }

        $groupModel->create([
            'name' => $this->argument('name'),
            'slug' => $this->argument('slug'),
            'description' => $this->argument('description'),
        ]);

        $this->info('Group created successfully!');

        return 0;
    }
}
