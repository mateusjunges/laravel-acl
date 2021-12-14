<?php

namespace Junges\ACL\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission
{
    public function groups(): BelongsToMany;
}