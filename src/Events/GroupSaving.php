<?php

namespace Junges\ACL\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupSaving
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
}
