<?php

namespace Junges\ACL\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class GroupSaving
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group;

    /**
     * Create a new event instance.
     *
     * @param $attributes
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
