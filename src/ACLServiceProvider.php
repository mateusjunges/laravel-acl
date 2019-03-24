<?php

namespace Junges\ACL;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider;
use Junges\ACL\Console\Commands\CreateGroup;
use Junges\ACL\Console\Commands\CreatePermission;

class ACLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param Dispatcher $events
     * @param Repository $config
     * @param Factory $view
     * @return void
     */
    public function boot(Dispatcher $events, Repository $config, Factory $view)
    {

        $this->loadMigrationsFrom(__DIR__ .'/database/migrations');

        //Publishes config
        $this->publishConfig();

        //Publishes views
        $this->loadViews();

        //Load commands
        $this->loadCommands();
    }


    /**
     * Load and publishes the views folder
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ .'/../resources/views', 'acl');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/junges/acl'),
        ], 'views');
    }

    /**
     * Load and publishes the acl.php configuration file
     */
    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/acl.php' => config_path('acl.php'),
        ], 'config');
    }

    public function loadCommands()
    {
        if ($this->app->runningInConsole())
            $this->commands([
                CreatePermission::class,
                CreateGroup::class,
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