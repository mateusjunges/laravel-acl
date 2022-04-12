<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'acl:install';
    protected $description = 'Install all of the Laravel ACL resources';

    public function handle(): int
    {
        $this->comment('Publishing Laravel ACL Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'acl-migrations']);
        $this->comment('Using default migrations by default.');

        $this->comment('Publishing Laravel ACL configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'acl-config']);

        $this->info('Laravel ACL installed successfully');

        $this->comment('Remember to use the <info>HasPermissions</info> inside your User model.');

        return Command::SUCCESS;
    }
}
