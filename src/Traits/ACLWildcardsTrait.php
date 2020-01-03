<?php

namespace Junges\ACL\Traits;

trait ACLWildcardsTrait
{
    /**
     * Check if the user has a permission, but only if this permission is directly associated to the user.
     *
     * @param $permissionSlug
     * @return bool
     */
    public function hasPermissionWithWildcards(string $permissionSlug): bool
    {
        $permissionSlug = str_replace('*', '%', $permissionSlug);

        return (bool) $this->permissions()
            ->where('slug', 'like', $permissionSlug)
            ->count();
    }
}
