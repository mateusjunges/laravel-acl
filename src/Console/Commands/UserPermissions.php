<?php

namespace Junges\ACL\Console\Commands;

use App\User;
use Illuminate\Console\Command;

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
        try{
            $userParameter = $this->argument('user');
            $databaseOperator = env('DB_CONNECTION') === 'pgsql' ? 'ilike' : 'LIKE';

            if (is_numeric($userParameter)) $user = User::find((int)$userParameter);
            else if(is_string($userParameter)) $user = User::where('name', $databaseOperator, $userParameter)->first();

            if (is_null($user)){
                $this->error('User not found');
                return;
            }

            $permissions = $user->permissions->map(function ($permission){
               return [
                   'name'        => $permission->name,
                   'slug'        => $permission->slug,
                   'description' => $permission->description,
               ];
            });

            $this->info('Displaying '.$user->name.'\'s permissions:');
            if ($permissions->count()  == 0){
                $this->alert('No permissions found');
                return;
            }

            $headers = ['Name', 'Slug', 'Description'];
            $this->table($headers, $permissions);

        }catch (\Exception $exception){
            $this->error('Something went wrong');
        }
    }
}
