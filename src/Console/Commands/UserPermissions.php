<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class UserPermissions extends Command
{
    protected $signature = 'user:permissions {user}';
    protected $description = 'Show user permissions';

    public function handle(): int
    {
        $userParameter = $this->argument('user');
        $userModel = app(config('acl.models.user'));
        $user = null;

        if (is_numeric($userParameter)) {
            $user = $userModel->find((int) $userParameter);
        } elseif (is_string($userParameter)) {
            $table = config('acl.tables.users');
            $columns = $this->verifyColumns($table);

            $columns = collect($columns)
                ->filter(fn (array $item) => $item['isset_column'])
                ->map(fn (array $item) => $item['column'])
                ->toArray();

            $columns = array_unique($columns);
            $columns = array_filter($columns, 'strlen');

            $user = $userModel->where(function ($query) use ($userParameter, $columns) {
                foreach ($columns as $column) {
                    $query->orWhere($column, $userParameter);
                }
            });
            $user = $user->first();
        }

        if (is_null($user)) {
            $this->error('User not found');

            return Command::SUCCESS;
        }

        $permissions = $user->permissions->map(function ($permission) {
            return [
                'name' => $permission->name,
                'slug' => $permission->slug,
                'description' => $permission->description,
            ];
        });

        $this->info('Displaying '.$user->name.'\'s permissions:');

        if ($permissions->count() == 0) {
            $this->alert('No permissions found');

            return Command::SUCCESS;
        }

        $headers = ['Name', 'Slug', 'Description'];

        $this->table($headers, $permissions);

        return Command::SUCCESS;
    }

    /**
     * @param $table
     *
     * @return array
     */
    private function verifyColumns($table)
    {
        return [
            [
                'column' => 'username',
                'isset_column' => Schema::hasColumn($table, 'username'),
            ],
            [
                'column' => 'name',
                'isset_column' => Schema::hasColumn($table, 'name'),
            ],
            [
                'column' => 'email',
                'isset_column' => Schema::hasColumn($table, 'email'),
            ],
        ];
    }
}
