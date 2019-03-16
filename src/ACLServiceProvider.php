<?php

namespace MateusJunges\ACL;

use Illuminate\Support\ServiceProvider;

class ACLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ .'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ .'/database/migrations');
        $this->loadViewsFrom(__DIR__ .'/resources/views', 'acl');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}