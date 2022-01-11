<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;
use Junges\ACL\AclRegistrar;

class ResetCacheCommand extends Command
{
    protected $signature = 'acl:reset-cache';

    protected $description = 'Reset the permissions cache';

    public function handle()
    {
        if (app(AclRegistrar::class)->forgetCachedPermissions()) {
            $this->info('Permissions cache flushed successfully!');
            return Command::SUCCESS;
        }

        $this->error('Cannot flush cache.');

        return Command::FAILURE;
    }
}