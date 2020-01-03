<?php

namespace Junges\ACL\Exceptions\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

class ACLNotInstalledSolution implements RunnableSolution
{
    public function getSolutionTitle(): string
    {
        return 'You Haven\'t Installed laravel-acl fully';
    }

    public function getSolutionDescription(): string
    {
        return 'You forgot to run `php artisan acl:install` or `php artisan migrate` to create the tables.';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Installation Docs' => 'https://mateusjunges.github.io/laravel-acl/guide/getting-started.html#install-using-acl-install-command',
        ];
    }

    public function getSolutionActionDescription(): string
    {
        return 'Pressing the button below will try to install the package or run the migration depending on what\'s missing.';
    }

    public function getRunButtonText(): string
    {
        return 'Install';
    }

    public function run(array $parameters = [])
    {
        if (! $this->determineRunAction()) {
            Artisan::call('vendor:publish', ['--tag' => 'acl-migrations']);
            Artisan::call('vendor:publish', ['--tag' => 'acl-config']);
        }

        Artisan::call('migrate');
    }

    public function getRunParameters(): array
    {
        return [];
    }

    private function determineRunAction()
    {
        $filesystem = new Filesystem();
        $files = $filesystem->glob(__DIR__.'/../database/migrations/*.php');

        $filesExist = Collection::make($files)->filter(function ($file) use ($filesystem) {
            return $filesystem->exists(app()->databasePath('/migrations').$file);
        })->count();

        return $filesExist;
    }
}
