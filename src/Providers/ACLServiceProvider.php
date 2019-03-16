<?php

namespace JungesSolutions\ACL;

use Illuminate\Support\ServiceProvider;

class ACLServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__. '/database/migrations');
        $this->loadViewsFrom(__DIR__. '/resources/views', 'acl');
    }

    public function register()
    {
        //
    }
}