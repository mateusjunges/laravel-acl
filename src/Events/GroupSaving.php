<?php

namespace Junges\ACL\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupSaving
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group;

    /**
     * Create a new event instance.
     *
     * @param $group
     */
    public function __construct($group)
    {
        $this->group = $group;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        //
    }
}
