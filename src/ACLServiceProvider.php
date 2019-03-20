<?php

namespace MateusJunges\ACL;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider;
use MateusJunges\ACL\Http\Events\BuildMenu;
use MateusJunges\ACL\Http\ViewComposers\ACLComposer;

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

        $this->loadRoutesFrom(__DIR__ .'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ .'/../database/migrations');

        //Publishes config
        $this->publishConfig();

        //Publishes views
        $this->loadViews();

        //Publishes assets
        $this->publishAssets();
    }

    /**
     * Load and publishes the assets
     */
    public function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../public/' => public_path('/vendor/mateusjunges/acl'),
        ], 'assets');
    }

    /**
     * Load and publishes the views folder
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ .'/resources/views', 'acl');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/mateusjunges/acl'),
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