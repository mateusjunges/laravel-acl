<?php

namespace Junges\ACL\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionSaving
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Model $permission;

    public function __construct($permission)
    {
        $this->permission = $permission;
    }
}
