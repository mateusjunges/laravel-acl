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

        //Publishes
        $this->publishes([
           __DIR__ . '/config/acl.php' => config_path('acl.php'),
           __DIR__ . '/resources/views' => resource_path('views/vendor/mateusjunges/acl'),
        ]);
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