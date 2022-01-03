<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\Exceptions\PermissionAlreadyExists;

class CreatePermission extends Command
{
    protected $signature = 'permission:create {name} {slug} {description}';
    protected $description = 'Create a new system permission on permissions table';

    public function handle(): int
    {
        $permissionModel = app(config('acl.models.permission'));

        $permission = $permissionModel->where('slug', $this->argument('slug'))
            ->orWhere('name', $this->argument('name'))
            ->first();

        if (! is_null($permission)) {
            throw PermissionAlreadyExists::create();
        }

        $permissionModel->create([
            'name' => $this->argument('name'),
            'slug' => $this->argument('slug'),
            'description' => $this->argument('description'),
        ]);

        $this->info('Permission created successfully!');

        return Command::SUCCESS;
    }
}
