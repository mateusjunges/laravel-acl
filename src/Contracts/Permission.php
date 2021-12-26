<?php

namespace Junges\ACL\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property Collection groups
 * @mixin Model
 */
interface Permission
{
    public function groups(): BelongsToMany;
}
