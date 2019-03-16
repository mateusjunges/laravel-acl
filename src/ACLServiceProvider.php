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

        //Publishes config
        $this->publishes([
           __DIR__ . '/config/acl.php' => config_path('acl.php'),
        ], 'config');

        //Publishes views
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/mateusjunges/acl'),
        ], 'views');

        //Publishes assets
        $this->publishes([
            __DIR__ . '/public/' => public_path('/vendor/mateusjunges/acl'),
        ], 'assets');

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