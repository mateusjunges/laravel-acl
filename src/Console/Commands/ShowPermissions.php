<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Models\Permission;

class ShowPermissions extends Command
{
    protected $signature = 'permission:show {--group=}';
    protected $description = 'Show all database permissions or the permissions for a specific group';

    public function handle(): int
    {
        $groupParameter = $this->option('group');

        if ($groupParameter) {
            if (is_numeric($groupParameter)) {
                $group = app(GroupContract::class)::find((int) $groupParameter);
            } elseif (is_string($groupParameter)) {
                $group = app(GroupContract::class)::where('name', $groupParameter)->first();
            }

            if (is_null($group)) {
                $this->error('Group does not exist!');

                return 0;
            }

            $permissions = $group->permissions->map(function ($permission) {
                return [
                    'permission' => $permission->name,
                    'guard' => $permission->guard,
                    'description' => $permission->description,
                ];
            });
            $this->info('Showing '.$group->name.' permissions:');
        } else {
            $this->info('Displaying all permissions:');
            $permissions = Permission::all(['name', 'guard_name', 'description']);
        }

        $headers = ['Permission', 'Guard', 'Description'];

        if ($permissions->count() == 0) {
            $this->alert('No permissions found.');

            return Command::SUCCESS;
        }

        $this->table($headers, $permissions->toArray());

        return Command::SUCCESS;
    }
}
