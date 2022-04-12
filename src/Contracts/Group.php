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
    /**
     * A Group may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Find a group by its name and optionally guard name.
     *
     * @param string $name
     * @param string|null $guardName
     * @return static
     */
    public static function findByName(string $name, string $guardName = null): self;

    /**
     * Find a group by its id and optionally guard name.
     *
     * @param int $id
     * @param string|null $guardName
     * @return static
     */
    public static function findById(int $id, string $guardName = null): self;

    /**
     * Find or create a group by its name and optionally guard name.
     *
     * @param string $name
     * @param $guardName
     * @return static
     */
    public static function findOrCreate(string $name, $guardName = null): self;

    /**
     * Determine if the has the given permission.
     *
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission): bool;
}
