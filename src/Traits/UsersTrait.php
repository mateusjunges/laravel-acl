<?php

namespace Junges\ACL\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Junges\ACL\Exceptions\GroupDoesNotExistException;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;

trait UsersTrait
{
    /**
     * Return all user groups.
     *
     * @return mixed
     */
    public function groups()
    {
        return $this->belongsToMany(config('acl.models.group'), config('acl.tables.user_has_groups'));
    }

    /**
     * Return all user permissions.
     *
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.user_has_permissions'));
    }

    /**
     * Determine if a user has the specified group.
     * @param mixed $group
     * @return bool
     */
    public function hasGroup($group)
    {
        $model = app(config('acl.models.group'));
        $where = null;

        if (is_numeric($group)) {
            $where = ['id', $group];
        } elseif (is_string($group)) {
            $where = ['slug', $group];
        } elseif ($group instanceof $model) {
            $where = ['slug', $group->slug];
        }

        if ($group != null && $where != null) {
            return null !== $this->groups->where(...$where)->first();
        }

        return false;
    }

    /**
     * Determine if a user has a permission, regardless of whether it is direct or via group.
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $model = app(config('acl.models.permission'));
        $where = null;

        if (is_numeric($permission)) {
            $where = ['id', $permission];
        } elseif (is_string($permission)) {
            $where = ['slug', $permission];
        } elseif ($permission instanceof $model) {
            $where = ['slug', $permission->slug];
        }

        if ($permission != null && $where != null) {
            return (bool) ($this->permissions->where(...$where)->count())
                || $this->hasPermissionThroughGroup($permission);
        }

        return false;
    }

    /**
     * Determine if a user has a permission directly associated.
     * @param $permission
     * @return bool
     */
    public function hasDirectPermission($permission)
    {
        $model = app(config('acl.models.permission'));
        $where = null;

        if (is_numeric($permission)) {
            $where = ['id', $permission];
        } elseif (is_string($permission)) {
            $where = ['slug', $permission];
        } elseif ($permission instanceof $model) {
            $where = ['slug', $permission->slug];
        }

        if ($permission != null && $where != null) {
            return (bool) ($this->permissions->where(...$where)->count());
        }

        return false;
    }

    /**
     * Determine if the user has a group which has the required permission.
     * @param $permission
     * @return bool
     */
    public function hasPermissionThroughGroup($permission)
    {
        $model = app(config('acl.models.permission'));
        $where = null;

        if (is_numeric($permission)) {
            $where = ['id', $permission];
        } elseif (is_string($permission)) {
            $where = ['slug', $permission];
        } elseif ($permission instanceof $model) {
            $where = ['slug', $permission->slug];
        }

        if ($permission != null && $where != null) {
            foreach ($this->groups as $group) {
                if ($group->permissions->where(...$where)->count() > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Retrieves all permissions a user has via groups.
     *
     * @return mixed
     */
    public function permissionViaGroups()
    {
        return $this->load('groups', 'groups.permissions')
            ->groups->flatMap(function ($group) {
                return $group->permissions;
            })->sort()->values();
    }

    /**
     * Return all the permissions a user has, both directly and via groups.
     */
    public function getAllPermissions()
    {
        $permissions = $this->permissions;
        if ($this->groups) {
            $permissions = $permissions->merge($this->permissionViaGroups());
        }

        return $permissions->sort()->values();
    }

    /**
     * @param array $permissions
     *
     * @return mixed
     */
    protected function getPermissionIds(array $permissions)
    {
        $model = app(config('acl.models.permission'));

        return collect(array_map(function ($permission) use ($model) {
            if (is_numeric($permission)) {
                $_permission = $model->find($permission);
            } elseif (is_string($permission)) {
                $_permission = $model->where('slug', $permission)->first();
            } elseif ($permission instanceof $model) {
                $_permission = $permission;
            }
            if (isset($_permission)) {
                if (! is_null($_permission)) {
                    return $_permission->id;
                }
            }
        }, $permissions));
    }

    /**
     * @param array $groups
     *
     * @return mixed
     */
    protected function getGroupIds(array $groups)
    {
        $model = app(config('acl.models.group'));

        return collect(array_map(function ($group) use ($model) {
            if ($group instanceof $model) {
                $_group = $group;
            } elseif (is_numeric($group)) {
                $_group = $model->find($group);
            } elseif (is_string($group)) {
                $_group = $model->where('slug', $group)->first();
            }
            if (isset($_group)) {
                if (! is_null($_group)) {
                    return $_group->id;
                }
            }
        }, $groups));
    }

    /**
     * Convert groups to group ids and throws exception if the group does not exist.
     *
     * @param $groups
     *
     * @throws GroupDoesNotExistException
     *
     * @return Collection
     */
    private function convertToGroupIds($groups)
    {
        $model = app(config('acl.models.group'));
        $groups = ! is_array($groups) ? [$groups] : $groups;

        return collect(array_map(function ($group) use ($model) {
            if ($group instanceof $model) {
                return $group->id;
            } elseif (is_numeric($group)) {
                $_group = $model->find($group);
                if ($_group instanceof $model) {
                    return $_group->id;
                } else {
                    throw GroupDoesNotExistException::withId($group);
                }
            } elseif (is_string($group)) {
                $_group = $model->where('slug', $group)->first();
                if ($_group instanceof $model) {
                    return $_group->id;
                } else {
                    throw GroupDoesNotExistException::withSlug($group);
                }
            }
        }, $groups));
    }

    /**
     * Check if the specified user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        $admin = config('acl.admin_permission', 'admin');

        return $this->hasPermission($admin);
    }

    /**
     * Convert permissions to permission ids and throw exception if the permission doesn't exit.
     *
     * @param $permissions
     *
     * @throws PermissionDoesNotExistException
     *
     * @return Collection
     */
    private function convertToPermissionIds($permissions)
    {
        $model = app(config('acl.models.permission'));
        $permissions = ! is_array($permissions) ? [$permissions] : $permissions;

        return collect(array_map(function ($permission) use ($model) {
            if ($permission instanceof $model) {
                return $permission->id;
            } elseif (is_numeric($permission)) {
                $_permission = $model->find($permission);
                if ($_permission instanceof $model) {
                    return $_permission->id;
                } else {
                    throw PermissionDoesNotExistException::withId($permission);
                }
            } elseif (is_string($permission)) {
                $_permission = $model->where('slug', $permission)->first();
                if ($_permission instanceof $model) {
                    return $_permission->id;
                } else {
                    throw PermissionDoesNotExistException::withSlug($permission);
                }
            }
        }, $permissions));
    }

    /**
     * Give permissions to the user.
     *
     * @param mixed $permissions
     *
     * @return mixed
     */
    public function assignPermissions(...$permissions)
    {
        $permissions = $this->getCorrectParameter($permissions);
        $permissions = $this->convertToPermissionIds($permissions);
        if ($permissions->count() == 0) {
            return false;
        }
        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
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
        $permissions = $this->getCorrectParameter($permissions);
        $permissions = $this->convertToPermissionIds($permissions);
        if ($permissions->count() == 0) {
            return false;
        }
        $this->permissions()->sync($permissions);

        return $this;
    }

    /**
     * Sync user groups on database.
     *
     * @param mixed ...$groups
     * @return $this|bool
     */
    public function syncGroups(...$groups)
    {
        $groups = $this->getCorrectParameter($groups);
        $groups = $this->convertToGroupIds($groups);

        if ($groups->count() == 0) {
            return false;
        }

        $this->groups()->sync($groups);

        return $this;
    }

    /**
     * Determine which type of parameter is being used.
     * @param $param
     * @return array
     */
    private function getCorrectParameter(array $param)
    {
        if (is_array($param[0])) {
            return $param[0];
        }

        return $param;
    }

    /**
     * Revoke permissions from the user.
     *
     * @param array $permissions
     *
     * @return $this
     */
    public function revokePermissions(...$permissions)
    {
        $permissions = $this->getCorrectParameter($permissions);
        $permissions = $this->getPermissionIds($permissions);
        $this->permissions()->detach($permissions);

        return $this;
    }

    /**
     * Assign a group to a user.
     *
     * @param array $groups
     *
     * @return $this|bool
     */
    public function assignGroup(...$groups)
    {
        $groups = $this->getCorrectParameter($groups);
        $groups = $this->convertToGroupIds($groups);
        if ($groups->count() == 0) {
            return false;
        }
        $this->groups()->syncWithoutDetaching($groups);

        return $this;
    }

    /**
     * Revoke user access to a group.
     *
     * @param array $groups
     *
     * @return $this|bool
     */
    public function revokeGroup(...$groups)
    {
        $groups = $this->getCorrectParameter($groups);
        $groups = $this->getGroupIds($groups);
        if ($groups->count() == 0) {
            return false;
        }
        $this->groups()->detach($groups);

        return $this;
    }

    /**
     * Check if a user has any permission.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAnyPermission(...$permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has any group.
     *
     * @param array $groups
     *
     * @return bool
     */
    public function hasAnyGroup(...$groups)
    {
        foreach ($groups as $group) {
            if ($this->hasGroup($group)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all specified groups.
     *
     * @param array $groups
     *
     * @return bool
     */
    public function hasAllGroups(...$groups)
    {
        foreach ($groups as $group) {
            if (! $this->hasGroup($group)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the user has all specified permissions.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAllPermissions(...$permissions)
    {
        foreach ($permissions as $permission) {
            if (! $this->hasPermission($permission)) {
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
     * Convert permission id or permission slug to permission model.
     *
     * @param $permissions
     *
     * @return array
     */
    protected function convertToPermissionModels($permissions)
    {
        $permissionModel = app(config('acl.models.permission'));
        if ($permissions instanceof Collection) {
            $permissions = $permissions->all();
        }
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        return array_map(function ($permission) use ($permissionModel) {
            $_permission = null;
            if ($permission instanceof $permissionModel) {
                $_permission = $permission;
            } elseif (is_numeric($permission)) {
                $_permission = $permissionModel->find($permission);
                if (is_null($_permission)) {
                    throw PermissionDoesNotExistException::withId($permission);
                }
            } elseif (is_string($permission)) {
                $_permission = $permissionModel->where('slug', $permission)->first();
                if (is_null($_permission)) {
                    throw PermissionDoesNotExistException::withSlug($permission);
                }
            }
            if (is_null($_permission)) {
                throw PermissionDoesNotExistException::nullPermission();
            }

            return $_permission;
        }, $permissions);
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
                $query->where(function ($query) use ($permissions) {
                    foreach ($permissions as $permission) {
                        $query->orWhere(config('acl.tables.permissions').'.id', $permission->id);
                    }
                });
            });
            if (count($groupsWithPermissions) > 0) {
                $query->orWhereHas('groups', function ($query) use ($groupsWithPermissions) {
                    $query->where(function ($query) use ($groupsWithPermissions) {
                        foreach ($groupsWithPermissions as $groupsWithPermission) {
                            $query->orWhere(config('acl.tables.groups').'.id', $groupsWithPermission->id);
                        }
                    });
                });
            }
        });
    }

    /**
     * Revoke all directly associated user permissions.
     *
     * @return mixed
     */
    public function revokeAllPermissions()
    {
        $this->permissions()->detach();

        return $this;
    }

    /**
     * Revoke all user groups.
     *
     * @return mixed
     */
    public function revokeAllGroups()
    {
        $this->groups()->detach();

        return $this;
    }

    /**
     *  Assign all system groups to the user.
     *
     * @return mixed
     */
    public function assignAllGroups()
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
    public function assignAllPermissions()
    {
        $permissionModel = app(config('acl.models.permission'));
        $permissionModel->all()->map(function ($permission) {
            return $this->assignPermissions([$permission]);
        });

        return $this;
    }
}
