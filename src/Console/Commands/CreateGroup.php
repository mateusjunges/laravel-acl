<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Junges\ACL\AclRegistrar;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;

class CreateGroup extends Command
{
    protected $signature = 'group:create
        {name : The name of the group}
        {guard? : The name of the guard}
        {permissions? : A list of the permissions to assign to the group created. (Separated by `|`)}
        {--team-id=}';

    protected $description = 'Create a new group on groups table';

    public function handle(): int
    {
        /** @var GroupContract $groupClass */
        $groupClass = app(GroupContract::class);

        $teamId = getPermissionsTeamId();

        setPermissionsTeamId($this->option('team-id') ?: null);

        if (! AclRegistrar::$teams && $this->option('team-id')) {
            $this->warn('Teams feature is currently disabled.');

            return Command::SUCCESS;
        }

        $group = $groupClass::findOrCreate(
            $this->argument('name'),
            $this->argument('guard')
        );

        setPermissionsTeamId($teamId);

        $teamsKey = AclRegistrar::$teamsKey;

        if (AclRegistrar::$teams && $this->option('team-id') && is_null($group->$teamsKey)) {
            $this->warn("Group `{$group->name}` already exists on global team.");
        }

        $group->assignPermission(
            $this->makePermissions($this->argument('permissions'))
        );

        $this->info("Group `{$group->name}` ".($group->wasRecentlyCreated ? 'created' : 'updated'));

        return Command::SUCCESS;
    }

    protected function makePermissions(string $permissions = null): Collection
    {
        if (empty($permissions)) {
            return collect();
        }

        /** @var \Junges\ACL\Contracts\Permission $permissionClass */
        $permissionClass = app(PermissionContract::class);

        $permissions = explode('|', $permissions);

        return collect($permissions)->map(function (string $permission) use ($permissionClass) {
            return $permissionClass::findOrCreate(trim($permission), $this->argument('guard'));
        });
    }
}
