<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\Contracts\Permission as PermissionContract;

class CreatePermission extends Command
{
    protected $signature = 'permission:create
        {name : The name of the permission}
        {guard? : The guard name}';

    protected $description = 'Create a new system permission on permissions table';

    public function handle(): int
    {
        /** @var PermissionContract $permissionClass */
        $permissionClass = app(PermissionContract::class);

        $permission = $permissionClass::findOrCreate(
            $this->argument('name'),
            $this->argument('guard')
        );

        $this->info("Permission `{$permission->name}` ".($permission->wasRecentlyCreated ? 'created' : 'already exists'));

        return Command::SUCCESS;
    }
}
