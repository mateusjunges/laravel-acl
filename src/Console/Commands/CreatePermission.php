<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\Exceptions\PermissionAlreadyExistsException;

class CreatePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:create {name} {slug} {description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new system permission on permissions table';

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
            $permissionModel = app(config('acl.models.permission'));

            try {
                $permission = $permissionModel->where('slug', $this->argument('slug'))
                    ->orWhere('name', $this->argument('name'))
                    ->first();
                if (! is_null($permission)) {
                    throw PermissionAlreadyExistsException::create();
                }
                $permissionModel->create([
                    'name'        => $this->argument('name'),
                    'slug'        => $this->argument('slug'),
                    'description' => $this->argument('description'),
                ]);
                $this->info('Permission created successfully!');
            } catch (\Exception $exception) {
                $this->error('Permission was not created!');
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
