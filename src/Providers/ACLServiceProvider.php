<?php

namespace Junges\ACL\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Junges\ACL\Console\Commands\CreateGroup;
use Junges\ACL\Console\Commands\CreatePermission;
use Junges\ACL\Console\Commands\InstallCommand;
use Junges\ACL\Console\Commands\ShowPermissions;
use Junges\ACL\Console\Commands\UserPermissions;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Macros\WithGroup;
use Junges\ACL\Macros\WithPermission;

class ACLServiceProvider extends ServiceProvider
{
    public function boot(Dispatcher $events, Repository $config, Factory $view)
    {
        $this->packagePublishables();

        $this->loadViews();

        $this->registerMacroHelpers();

        $this->registerCommands();

        $this->registerModelBindings();

        $this->loadTranslations();
    }

    protected function packagePublishables()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/acl.php',
            'acl'
        );

        $this->publishes([
            __DIR__ . '/../../config/acl.php',
        ], 'acl-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/create_permissions_table.php' => $this->getMigrationFilename('create_acl_permissions_table.php', 1),
            __DIR__ . '/../../database/migrations/create_groups_table.php' => $this->getMigrationFilename('create_acl_groups_table.php', 2),
            __DIR__ . '/../../database/migrations/create_model_has_permissions_table.php' => $this->getMigrationFilename('create_model_has_permissions_table.php', 3),
            __DIR__ . '/../../database/migrations/create_model_has_groups_table.php' => $this->getMigrationFilename('create_model_has_groups_table.php', 4),
            __DIR__ . '/../../database/migrations/create_group_has_permissions_table.php' => $this->getMigrationFilename('create_group_has_permissions_table.php', 5),
        ], 'acl-migrations');
    }

    protected function getMigrationFilename(string $filename, int $order): string
    {
        $timestamp = now()->addSeconds($order * 2)->format('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(fn ($path) => $filesystem->glob($path . '*_' . $filename))
            ->push($this->app->databasePath() . "/migrations/{$timestamp}_{$filename}")
            ->first();
    }

    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'acl');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/junges/acl'),
        ], 'acl-views');
    }

    protected function registerMacroHelpers()
    {
        Route::macro('withGroup', app(WithGroup::class)());
        Route::macro('withPermission', app(WithPermission::class)());
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePermission::class,
                ShowPermissions::class,
                CreateGroup::class,
                UserPermissions::class,
                InstallCommand::class,
            ]);
        }
    }

    protected function loadTranslations()
    {
        $translationsPath = __DIR__ . '/../../resources/lang';

        $this->loadTranslationsFrom($translationsPath, 'acl');

        $this->publishes([
            $translationsPath => base_path('resources/lang/vendor/acl'),
        ], 'acl-translations');
    }

    protected function registerModelBindings()
    {
        $config = $this->app->config['acl.models'];

        if (! $config) {
            return;
        }

        $this->app->bind(PermissionContract::class, $config['permission']);
        $this->app->bind(GroupContract::class, $config['group']);
    }
}
