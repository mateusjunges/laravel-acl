<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\Models\Group;
use Junges\ACL\Models\Permission;

class ShowPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:show {--group=}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all database permissions or the permissions for a specific group';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $groupParameter = $this->option('group');

        if ($groupParameter) {
            if (is_numeric($groupParameter)) {
                $group = Group::find((int) $groupParameter);
            } elseif (is_string($groupParameter)) {
                $group = Group::where('slug', $groupParameter)->first();
            }

            if (is_null($group)) {
                $this->error('Group does not exist!');

                return 0;
            }

            $permissions = $group->permissions->map(function ($permission) {
                return [
                    'permission' => $permission->name,
                    'slug' => $permission->slug,
                    'description' => $permission->description,
                ];
            });
            $this->info('Showing '.$group->name.' permissions:');
        } else {
            $this->info('Displaying all permissions:');
            $permissions = Permission::all(['name', 'slug', 'description']);
        }

        $headers = ['Permission', 'Slug', 'Description'];

        if ($permissions->count() == 0) {
            $this->alert('No permissions found.');

            return 0;
        }

        $this->table($headers, $permissions->toArray());

        return 0;
    }
}
