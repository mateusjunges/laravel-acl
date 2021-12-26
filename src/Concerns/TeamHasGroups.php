<?php

namespace Junges\ACL\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Junges\ACL\AclRegistrar;

/**
 * @mixin Model
 */
trait TeamHasGroups
{
    public static function bootTeamHasGroups()
    {
        static::deleting(function ($model) {
            $modelHasSoftDeleting = method_exists($model, 'isForceDeleting');
            $groupHasSoftDeleting = method_exists(app(AclRegistrar::class)->getGroupClass(), 'isForceDeleting');

            if ($modelHasSoftDeleting && ! $model->isForceDeleting()) {
                if ($groupHasSoftDeleting) {
                    $model->specificGroups()->delete();
                }

                return;
            }

            $model->groups()->detach();
            $model->permissions()->detach();

            $groupDelete = $groupHasSoftDeleting && $modelHasSoftDeleting && $model->isForceDeleting() ? 'forceDelete' : 'delete';
            $model->specificGroups()->{$groupDelete}();
        });
    }

    /**
     * A team may have multiple groups on multiple models.
     *
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            app(AclRegistrar::class)->getGroupClass(),
            config('acl.tables.model_has_groups'),
            AclRegistrar::$teamsKey,
            AclRegistrar::$pivotGroup
        );
    }

    /**
     * A team may have multiple specific groups.
     *
     * @return HasMany
     */
    public function specificGroups(): HasMany
    {
        return $this->hasMany(
            app(AclRegistrar::class)->getGroupClass(),
            AclRegistrar::$teamsKey
        );
    }

    /**
     * A team may have multiple permissions on multiple models.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            app(AclRegistrar::class)->getPermissionClass(),
            config('acl.tables.model_has_permissions'),
            AclRegistrar::$teamsKey,
            AclRegistrar::$pivotPermission
        );
    }
}
