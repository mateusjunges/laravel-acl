<?php

namespace Junges\ACL\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class UserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:permissions {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show user permissions';

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
     * @return mixed
     */
    public function handle()
    {
        try {
            $userParameter = $this->argument('user');
            if (is_numeric($userParameter)) {
                $user = User::find((int) $userParameter);
            } elseif (is_string($userParameter)) {
                $table = config('acl.tables.users');
                $columns = $this->verifyColumns($table);

                $columns = collect($columns)->map(function ($item) {
                    if ($item['isset_column']) {
                        return $item['column'];
                    }
                })->toArray();
                $columns = array_unique($columns);
                $columns = array_filter($columns, 'strlen');

                $userModel = app(config('acl.models.user'));

                $user = $userModel->where(function ($query) use ($userParameter, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, $userParameter);
                    }
                });
                $user = $user->first();
            }
            if (is_null($user)) {
                $this->error('User not found');

                return;
            }
            $permissions = $user->permissions->map(function ($permission) {
                return [
                    'name'        => $permission->name,
                    'slug'        => $permission->slug,
                    'description' => $permission->description,
                ];
            });
            $this->info('Displaying '.$user->name.'\'s permissions:');
            if ($permissions->count() == 0) {
                $this->alert('No permissions found');

                return;
            }
            $headers = ['Name', 'Slug', 'Description'];
            $this->table($headers, $permissions);
        } catch (\Exception $exception) {
            $this->error('Something went wrong');
        }
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
                'column'       => 'username',
                'isset_column' => Schema::hasColumn($table, 'username'),
            ],
            [
                'column'       => 'name',
                'isset_column' => Schema::hasColumn($table, 'name'),
            ],
            [
                'column'       => 'email',
                'isset_column' => Schema::hasColumn($table, 'email'),
            ],
        ];
    }
}
