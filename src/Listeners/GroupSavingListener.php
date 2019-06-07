<?php

namespace Junges\ACL\Listeners;

use Junges\ACL\Events\GroupSaving;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;

class GroupSavingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param GroupSaving $event
     *
     * @return void
     */
    public function handle(GroupSaving $event)
    {
        $group = $event->group;
        $groupModel = app(config('acl.models.group'));
        $groupAlreadyExists = $groupModel
           ->where('slug', $group->slug)
           ->orWhere('name', $group->name)
           ->first();
        if (! is_null($groupAlreadyExists)) {
            throw GroupAlreadyExistsException::create();
        }
    }
}
