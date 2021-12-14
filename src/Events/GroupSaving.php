<?php

namespace Junges\ACL\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupSaving
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Model $group;

    public function __construct($group)
    {
        $this->group = $group;
    }
}
