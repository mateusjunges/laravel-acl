<?php


namespace Junges\ACL;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Junges\ACL\Events\GroupSaving;
use Junges\ACL\Listeners\GroupSavingListener;

class ACLEventsServiceProvider extends ServiceProvider
{
    public $listen = [
        GroupSaving::class => [
            GroupSavingListener::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}
