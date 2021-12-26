<?php

namespace Junges\ACL\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property Collection permissions
 * @mixin Model
 */
interface Group
{
    public function permissions(): BelongsToMany;

    public static function findByName(string $name, string $guardName = null): self;

    public static function findById(int $id, string $guardName = null): self;

    public static function findOrCreate(string $name, $guardName = null): self;

    public function hasPermission($permission): bool;
}
