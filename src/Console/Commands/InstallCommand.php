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

        $this->comment('Remember to use the <info>UsersTrait</info> inside your User model.');

        if (! config('acl.offer_solutions', false)) {
            $this->line("\n<options=bold>".str_repeat('=', 70).'</>');

            $this->comment('Ignition Solutions are disabled by default.');
            $this->info('To use ignition solutions for exceptions:');
            $this->comment('- Set <fg=green>"offer_solutions" => true</> in the laravel-acl config file.');
            $this->comment('- Run <fg=magenta;options=bold>composer require facade/ignition --dev</> to get ignition.');

            $this->line('<options=bold>'.str_repeat('=', 70)."</>\n");
        }

        return Command::SUCCESS;
    }
}
