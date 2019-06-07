<?php

namespace Junges\ACL;

use Junges\ACL\Events\GroupSaving;
use Junges\ACL\Events\PermissionSaving;
use Junges\ACL\Listeners\GroupSavingListener;
use Junges\ACL\Listeners\PermissionSavingListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class ACLEventsServiceProvider extends ServiceProvider
{
    public $listen = [
        GroupSaving::class => [
            GroupSavingListener::class,
        ],
        PermissionSaving::class => [
            PermissionSavingListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
