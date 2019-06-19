<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;

class CreateGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:create {name} {slug} {description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new group on groups table';

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
            $groupModel = app(config('acl.models.group'));
            try {
                $group = $groupModel->where('slug', $this->argument('slug'))
                    ->orWhere('name', $this->argument('name'))
                    ->first();
                if (! is_null($group)) {
                    throw GroupAlreadyExistsException::create();
                }
                $groupModel->create([
                    'name'        => $this->argument('name'),
                    'slug'        => $this->argument('slug'),
                    'description' => $this->argument('description'),
                ]);
                $this->info('Group created successfully!');
            } catch (\Exception $exception) {
                $this->error('Group was not created!');
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
