<?php

namespace Junges\ACL\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Group
{
    public function permissions(): BelongsToMany;

    public function hasPermission($permission): bool;
}