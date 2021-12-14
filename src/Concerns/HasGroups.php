<?php

namespace Junges\ACL\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Junges\ACL\AclRegistrar;
use Junges\ACL\Contracts\Group;

trait HasGroups
{
    use HasPermissions;

    private $groupClass;

    public static function bootHasRoles()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->groups()->detach();
        });
    }

    public function getGroupClass()
    {
        if (! isset($this->groupClass)) {
            $this->groupClass = app(AclRegistrar::class)->getGroupClass();
        }

        return $this->groupClass;
    }

    /**
     * A model may have multiple groups of permissions.
     *
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        $modelHasGroups = config('acl.tables.model_has_groups');

        return $this->morphToMany(
            config('acl.models.group'),
            'model',
            $modelHasGroups,
            config('acl.column_names.model_morph_key'),
            AclRegistrar::$pivotGroup
        );
    }

    /**
     * Scope the model query to certain groups only.
     *
     * @param Builder $query
     * @param $groups
     * @param $guard
     * @return Builder
     */
    public function scopeGroup(Builder $query, $groups, $guard = null): Builder
    {
        if ($groups instanceof Collection) {
            $groups = $groups->all();
        }

        if (! is_array($groups)) {
            $groups = [$groups];
        }

        $groups = array_map(function ($group) use ($guard) {
            if ($group instanceof Group) {
                return $group;
            }

            $method = is_numeric($group) ? 'findById' : 'findByName';

            return $this->getGroupClass()->{$method}($group, $guard ?: $this->getDefaultGuardName());
        }, $groups);

        return $query->whereHas('groups', function (Builder $query) use ($groups) {
            $groupClass = $this->getGroupClass();
            $key = (new $groupClass)->getKeyName();
            $query->whereIn(config('acl.tables.groups').".$key", array_column($groups, $key));
        });
    }

    /**
     * Assign the given group to the model.
     *
     * @param ...$groups
     * @return void
     */
    public function assignGroup(...$groups): self
    {
        $groupClass = $this->getGroupClass();

        $groups = collect($groups)
            ->flatten()
            ->map(function ($group) {
                if (empty($group)) {
                    return false;
                }

                return $this->getStoredGroup($group);
            })
            ->filter(fn ($group) => $group instanceof Group)
            ->each(fn ($group) => $this->ensureModelSharesGuard($group))
            ->map(fn ($group) => [$group->getKeyName() => $group->getKey(), 'values' => []])
            ->pluck('values', (new $groupClass)->getKeyName())
            ->toArray();

        $model = $this->getModel();

        if ($model->exists) {
            $this->getGroupsRelation()->sync($groups, false);
            $model->load('groups');
        } else {
            /** @var Model $class */
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($groups, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }

                    $model->roles()->sync($groups, false);
                    $model->load('groups');
                }
            );
        }

        if (is_a($this, get_class($this->getPermissionClass()))) {
            $this->forgetCachedPermissions();
        }

        return $this;
    }

    /**
     * Revoke the given group from the model.
     *
     * @param $group
     * @return $this
     */
    public function revokeGroup($group): self
    {
        $this->getGroupsRelation()->detach($this->getStoredGroup($group));

        $this->load('groups');

        if (is_a($this, get_class($this->getPermissionClass()))) {
            $this->forgetCachedPermissions();
        }

        return $this;
    }

    /**
     * Remove all current groups and set the given ones.
     *
     * @param ...$groups
     * @return void
     */
    public function syncGroups(...$groups)
    {
        $this->getGroupsRelation()->detach();

        $this->assignGroup($groups);
    }

    public function hasGroup($groups, string $guard = null): bool
    {
        if (is_string($groups) && strpos($groups, '|') !== false) {
            $groups = $this->convertPipeToArray($groups);
        }

        if (is_string($groups)) {
            return $guard
                ? $this->groups->where('guard_name', $guard)->contains('name', $groups)
                : $this->groups->contains('name', $groups);
        }

        if (is_int($groups)) {
            $groupClass = $this->getGroupClass();
            $key = (new $groupClass)->getKeyName();

            return $guard
                ? $this->groups->where('guard_name', $guard)->contains($key, $groups)
                : $this->groups->contains($key, $groups);
        }

        if ($groups instanceof Group) {
            return $this->groups->contains($groups->getKeyName(), $groups->getKey());
        }

        if (is_array($groups)) {
            foreach ($groups as $group) {
                if ($this->hasGroup($group, $guard)) {
                    return true;
                }
            }

            return false;
        }

        return $groups->intersect($guard ? $this->groups->where('guard_name', $guard) : $this->groups)->isNotEmpty();
    }

    /**
     * Determine if the model has any of the given group(s).
     *
     * @param ...$groups
     * @return bool
     */
    public function hasAnyGroup(...$groups): bool
    {
        return $this->hasGroup($groups);
    }

    /**
     * Determine if the model has all of the given groups.
     *
     * @param $groups
     * @param string|null $guard
     * @return bool
     */
    public function hasAllGroups($groups, string $guard = null): bool
    {
        if (is_string($groups) && false !== strpos($groups, '|')) {
            $groups = $this->convertPipeToArray($groups);
        }

        if (is_string($groups)) {
            return $guard
                ? $this->groups->where('guard_name', $guard)->contains('name', $groups)
                : $this->groups->contains('name', $groups);
        }

        if ($groups instanceof Group) {
            return $this->roles->contains($groups->getKeyName(), $groups->getKey());
        }

        $groups = collect()->make($groups)->map(function ($group) {
            return $group instanceof Group ? $group->name : $group;
        });

        return $groups->intersect(
            $guard
                    ? $this->groups->where('guard_name', $guard)->pluck('name')
                    : $this->getGroupNames()
        ) == $groups;
    }

    /**
     * Determine if the model has exactly all the given groups.
     *
     * @param $groups
     * @param string|null $guard
     * @return bool
     */
    public function hasExactlyGroups($groups, string $guard = null): bool
    {
        if (is_string($groups) && false !== strpos($groups, '|')) {
            $groups = $this->convertPipeToArray($groups);
        }

        if (is_string($groups)) {
            $groups = [$groups];
        }

        if ($groups instanceof Group) {
            $groups = [$groups->name];
        }

        $groups = collect()
            ->make($groups)
            ->map(fn ($group) => $group instanceof Group ? $group->name : $group);

        return $this->groups->count() === $groups->count() && $this->hasAllGroups($groups, $guard);
    }

    /**
     * Return all permissions directly coupled to the model.
     *
     * @return Collection
     */
    public function getDirectPermissions(): Collection
    {
        return $this->permissions;
    }

    public function getGroupNames()
    {
        return $this->groups->pluck('name');
    }

    protected function getStoredGroup($group): Group
    {
        $groupClass = $this->getGroupClass();

        if (is_numeric($group)) {
            return $groupClass->findById($group, $this->getDefaultGuardName());
        }

        if (is_string($group)) {
            return $groupClass->findByName($group, $this->getDefaultGuardName());
        }

        return $group;
    }

    protected function getGroupsRelation(): BelongsToMany
    {
        return $this->groups();
    }

    protected function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (! in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}
