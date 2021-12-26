<?php

namespace Junges\ACL\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Junges\ACL\AclRegistrar;
use Junges\ACL\Contracts\Group;
use Junges\ACL\Contracts\Permission;
use Junges\ACL\Exceptions\GroupDoesNotExistException;
use Junges\ACL\Exceptions\GuardDoesNotMatch;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;
use Junges\ACL\Guard;

trait HasPermissions
{
    private $permissionClass;

    public static function bootHasPermissions()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->permissions()->detach();
        });
    }

    public function getPermissionClass()
    {
        if (! isset($this->permissionClass)) {
            $this->permissionClass = app(AclRegistrar::class)->getPermissionClass();
        }

        return $this->permissionClass;
    }

    /**
     * Return all user permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        $relation = $this->morphToMany(
            config('acl.models.permission'),
            'model',
            config('acl.tables.model_has_permissions'),
            config('acl.column_names.model_morph_key'),
            AclRegistrar::$pivotPermission
        );

        if (AclRegistrar::$teams) {
            return $relation->wherePivot(AclRegistrar::$teamsKey, getPermissionsTeamId());
        }

        return $relation;
    }

    /**
     * Scope the model query to certain permissions only.
     *
     * @param Builder $query
     * @param $permissions
     *
     * @return Builder
     */
    public function scopePermission(Builder $query, $permissions): Builder
    {
        $permissions = $this->convertToPermissionModels($permissions);

        $groupsWithPermissions = array_unique(array_reduce($permissions, function ($result, $permission) {
            return array_merge($result, $permission->groups->all());
        }, []));

        return $query->where(function ($query) use ($permissions, $groupsWithPermissions) {
            $query->whereHas('permissions', function ($query) use ($permissions) {
                $permissionClass = $this->getPermissionClass();
                $key = (new $permissionClass)->getKeyName();
                $query->whereIn(config('acl.tables.permissions').".$key", array_column($permissions, $key));
            });

            if (count($groupsWithPermissions) > 0) {
                $query->orWhereHas('groups', function ($query) use ($groupsWithPermissions) {
                    $groupClass = $this->getGroupClass();
                    $key = (new $groupClass)->getKeyName();
                    $query->whereIn(config('acl.tables.groups').".$key", array_column($groupsWithPermissions, $key));
                });
            }
        });
    }

    /**
     * Determine if a user has a permission, regardless of whether it is direct or via group.
     *
     * @param  int|string|Model  $permission
     * @return bool
     */
    public function hasPermission($permission, string $guardName = null): bool
    {
        /** @var Permission $permissionClass */
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById(
                $permission,
                $guardName ?? $this->getDefaultGuardName(),
            );
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExistException();
        }

        return $this->hasDirectPermission($permission) || $this->hasPermissionThroughGroup($permission);
    }

    /**
     * Same as hasPermission(), but avoid throwing an exception.
     *
     * @param $permission
     * @param $guardName
     * @return bool
     * @throws \Junges\ACL\Exceptions\GuardDoesNotMatch
     */
    public function checkPermission($permission, $guardName = null): bool
    {
        try {
            return $this->hasPermission($permission, $guardName);
        } catch (PermissionDoesNotExistException $exception) {
            return false;
        }
    }

    /**
     * Retrieves all permissions a user has via groups.
     *
     * @return Collection
     */
    public function getPermissionsViaGroups(): Collection
    {
        return $this->loadMissing('groups', 'groups.permissions')
            ->groups->flatMap(fn ($group) => $group->permissions)
            ->sort()
            ->values();
    }

    /**
     * Sync user permissions on database.
     *
     * @param array $permissions
     *
     * @return $this|bool
     */
    public function syncPermissions(...$permissions)
    {
        $this->permissions()->detach();

        return $this->assignPermission($permissions);
    }

    /**
     * Revoke permissions from the user.
     *
     * @param  mixed  $permissions
     *
     * @return $this
     */
    public function revokePermission($permissions): self
    {
        $permissions = is_array($permissions) || $permissions instanceof Collection
            ? $permissions
            : func_get_args();

        foreach ($permissions as $permission) {
            $this->permissions()->detach($this->getStoredPermission($permission));
        }

        if (is_a($this, get_class(app(AclRegistrar::class)->getGroupClass()))) {
            $this->forgetCachedPermissions();
        }

        $this->load('permissions');

        return $this;
    }

    public function getPermissionNames(): Collection
    {
        return $this->permissions->pluck('name');
    }

    public function getStoredPermission($permissions)
    {
        $permissionClass = $this->getPermissionClass();

        if (is_numeric($permissions)) {
            return $permissionClass->findById($permissions, $this->getDefaultGuardName());
        }

        if (is_string($permissions)) {
            return $permissionClass->findByName($permissions, $this->getDefaultGuardName());
        }

        if (is_array($permissions)) {
            return $permissionClass
                ->whereIn('name', $permissions)
                ->whereIn('guard_name', $this->getGuardNames())
                ->get();
        }

        return $permissions;
    }

    public function forgetCachedPermissions()
    {
        app(AclRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Check if the model has all the requested Direct permissions.
     *
     * @param ...$permissions
     * @return bool
     */
    public function hasAllDirectPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->hasDirectPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the model has any of the requested Direct permissions.
     *
     * @param ...$permissions
     * @return bool
     */
    public function hasAnyDirectPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->hasDirectPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a user has any permission.
     *
     * @param  mixed  $permissions
     *
     * @return bool
     */
    public function hasAnyPermission($permissions): bool
    {
        $permissions = is_array($permissions) || $permissions instanceof Collection ? $permissions : func_get_args();

        foreach ($permissions as $permission) {
            if ($this->checkPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all specified permissions.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAllPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->checkPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the user has any group.
     *
     * @param  mixed  $groups
     *
     * @return bool
     */
    public function hasAnyGroup($groups): bool
    {
        $groups = is_array($groups) || $groups instanceof Collection ? $groups : func_get_args();
        foreach ($groups as $group) {
            if ($this->hasGroup($group)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user has a group which has the required permission.
     *
     * @param  Permission  $permission
     * @return bool
     */
    public function hasPermissionThroughGroup(Permission $permission): bool
    {
        return $this->hasGroup($permission->groups);
    }

    /**
     * Determine if a user has a permission directly associated.
     *
     * @param int|string|Model $permission
     * @param string|null $guardName
     * @return bool
     */
    public function hasDirectPermission($permission, string $guardName = null): bool
    {
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $guardName ?? $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $guardName ?? $this->getDefaultGuardName());
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExistException();
        }

        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }

    /**
     * Return all the permissions the model has via groups.
     *
     * @return Collection
     */
    public function getPermissionsThroughGroups(): Collection
    {
        return $this->loadMissing('groups', 'groups.permissions')
            ->groups->flatMap(fn ($group) => $group->permissions)
            ->sort()->values();
    }

    /**
     * Return all the permissions a user has, both directly and via groups.
     *
     * @return Collection
     */
    public function getAllPermissions(): Collection
    {
        $permissions = $this->permissions;

        if ($this->groups) {
            $permissions = $permissions->merge($this->getPermissionsViaGroups());
        }

        return $permissions->sort()->values();
    }

    /**
     * Give the given permissions to the model.
     *
     * @param mixed $permissions
     *
     * @return self|bool
     */
    public function assignPermission($permissions): self
    {
        $permissions = collect(is_array($permissions) || $permissions instanceof Collection ? $permissions : func_get_args())
            ->flatten()
            ->reduce(function ($array, $permission) {
                if (empty($permission)) {
                    return $array;
                }

                $permission = $this->getStoredPermission($permission);

                if (! $permission instanceof Permission) {
                    return $array;
                }

                $this->ensureModelSharesGuard($permission);

                $array[$permission->getKey()] = AclRegistrar::$teams && ! is_a($this, Group::class)
                    ? [AclRegistrar::$teamsKey => getPermissionsTeamId()] : [];

                return $array;
            }, []);

        $model = $this->getModel();

        if ($model->exists) {
            $this->permissions()->sync($permissions, false);
            $model->load('permissions');
        } else {
            /** @var Model $class */
            $class = get_class($model);

            $class::saved(
                function ($object) use ($permissions, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->permissions()->sync($permissions, false);
                    $model->load('permissions');
                }
            );
        }

        if (is_a($this, get_class(app(AclRegistrar::class)->getGroupClass()))) {
            $this->forgetCachedPermissions();
        }

        return $this;
    }

    /**
     * Check if the user has all specified groups.
     *
     * @param array $groups
     *
     * @return bool
     */
    public function hasAllGroups(...$groups): bool
    {
        foreach ($groups as $group) {
            if (! $this->hasGroup($group)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Scope the model query to certain groups only.
     *
     * @param Builder $query
     * @param $groups
     *
     * @return Builder
     */
    public function scopeGroup(Builder $query, $groups): Builder
    {
        $groupModel = app(config('acl.models.group'));

        if ($groups instanceof Collection) {
            $groups = $groups->all();
        }

        if (! is_array($groups)) {
            $groups = [$groups];
        }

        $groups = array_map(function ($group) use ($groupModel) {
            $_group = null;

            if ($group instanceof $groupModel) {
                $_group = $group;
            }

            if (is_numeric($group)) {
                $_group = $groupModel->find($group);

                if (is_null($_group)) {
                    throw GroupDoesNotExistException::withId($group);
                }
            } elseif (is_string($group)) {
                $_group = $groupModel->where('slug', $group)->first();

                if (is_null($_group)) {
                    throw GroupDoesNotExistException::withSlug($group);
                }
            }

            if (is_null($_group)) {
                throw GroupDoesNotExistException::nullGroup();
            }

            return $_group;
        }, $groups);

        return $query->whereHas('groups', function ($query) use ($groups) {
            $query->where(function ($query) use ($groups) {
                foreach ($groups as $group) {
                    if (! is_null($group)) {
                        $query->orWhere(config('acl.tables.groups').'.id', $group->id);
                    }
                }
            });
        });
    }

    /**
     * Revoke all directly associated user permissions.
     *
     * @return self
     */
    public function revokeAllPermissions(): self
    {
        $this->permissions()->detach();

        return $this;
    }

    /**
     * Revoke all user groups.
     *
     * @return self
     */
    public function revokeAllGroups(): self
    {
        $this->groups()->detach();

        return $this;
    }

    /**
     *  Assign all system groups to the user.
     *
     * @return self
     */
    public function assignAllGroups(): self
    {
        $groupModel = app(config('acl.models.group'));

        $groupModel->all()->map(function ($group) {
            return $this->assignGroup([$group]);
        });

        return $this;
    }

    /**
     * Assign all system permissions to the specified user.
     *
     * @return $this
     */
    public function assignAllPermissions(): self
    {
        $permissionModel = app(config('acl.models.permission'));

        $permissionModel->all()->map(function ($permission) {
            return $this->assignPermission([$permission]);
        });

        return $this;
    }

    protected function getGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }

    protected function ensureModelSharesGuard($groupOrPermission)
    {
        if (! $this->getGuardNames()->contains($groupOrPermission->guard_name)) {
            throw GuardDoesNotMatch::create($groupOrPermission->guard_name, $this->getGuardNames());
        }
    }

    /**
     * Convert permission id or permission slug to permission model.
     *
     * @param $permissions
     *
     * @return array
     */
    protected function convertToPermissionModels($permissions): array
    {
        if ($permissions instanceof Collection) {
            $permissions = $permissions->all();
        }

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        return array_map(function ($permission) {
            if ($permission instanceof Permission) {
                return $permission;
            }

            $method = is_string($permission) ? 'findByName' : 'findById';

            return $this->getPermissionClass()->{$method}($permission, $this->getDefaultGuardName());
        }, $permissions);
    }
}
