<?php

namespace Junges\ACL\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionSaving
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $permission;

    /**
     * Create a new event instance.
     *
     * @param $permission
     */
    public function __construct($permission)
    {
        $this->permission = $permission;
    }
}
