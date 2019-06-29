<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Laravel ACL resources';

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
        $this->comment("Publishing Laravel ACL Migrations...");
        $this->callSilent('vendor:publish', ['--tag' => 'acl-migrations']);
        $this->comment("Using default migrations by default.");

        $this->comment("Publishing Laravel ACL configuration...");
        $this->callSilent('vendor:publish', ['--tag' => 'acl-config']);


        $this->info("Laravel ACL installed successfully");

        $this->comment("Remember to use the UsersTrait inside your User model.");
    }
}
