<?php

namespace Junges\ACL\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Junges\ACL\Http\Models\Group;

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
     *
     */
    public function broadcastOn()
    {
        //
    }
}
