<?php

namespace Junges\ACL\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;
use Junges\ACL\Exceptions\UserDoesNotExistException;

trait GroupsTrait
{
    use SoftDeletes;

    /**
     * Return all group permissions.
     *
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.group_has_permissions'));
    }

    /**
     * Used only to fill the group form.
     *
     * @param $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        $where = null;
        $model = app(config('acl.models.permission'));
        if (is_numeric($permission)) {
            $where = ['id', $permission];
        } elseif (is_string($permission)) {
            $where = ['slug', $permission];
        } elseif ($permission instanceof $model) {
            $where = ['slug', $permission->slug];
        }
        if ($permission != null && $where != null) {
            return null !== $this->permissions->where(...$where)->first();
        }

        return false;
    }

    /**
     * Return all users who has a group.
     *
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(config('acl.models.user'), config('acl.tables.user_has_groups'));
    }

    /**
     * Add permissions to a group.
     *
     * @param array $permissions
     *
     * @return $this|bool
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
     * Sync group permissions on database.
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
     * Remove group permissions.
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
     * Retrive permission id for each one of the permissions array.
     *
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
     * Convert permissions to permission ids and throws exception if the permission does not exist.
     *
     * @param $permissions
     *
     * @throws PermissionDoesNotExistException
     *
     * @return \Illuminate\Support\Collection
     */
    private function convertToPermissionIds($permissions)
    {
        $model = app(config('acl.models.permission'));
        $permissions = is_array($permissions) ? $permissions : [$permissions];

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
     * Retrive a user model for each one of the users id array.
     *
     * @param array $users
     *
     * @return mixed
     */
    protected function getAllUsers(array $users)
    {
        $model = app(config('acl.models.user'));

        return collect(
            array_map(function ($user) use ($model) {
                if ($user instanceof $model) {
                    $_user = $user;
                } elseif (is_numeric($user)) {
                    $_user = $model->find($user);
                } elseif (is_string($user)) {
                    $_user = $model->where('name', $user)->first();
                }
                if (isset($_user)) {
                    if (! is_null($_user)) {
                        return $_user->id;
                    }
                }
            }, $users));
    }

    /**
     * Convert user to users id and throws exception if the user does not exist.
     *
     * @param $users
     *
     * @return \Illuminate\Support\Collection
     */
    private function convertToUserId($users)
    {
        $model = app(config('acl.models.user'));
        $users = is_array($users) ? $users : [$users];

        return collect(array_map(function ($user) use ($model) {
            if ($user instanceof $model) {
                return $user->id;
            } elseif (is_numeric($user)) {
                $_user = $model->find($user);
                if ($_user instanceof $model) {
                    return $_user->id;
                } else {
                    throw UserDoesNotExistException::withId($user);
                }
            } elseif (is_string($user)) {
                $_user = $model->where('name', $user)->first();
                if ($_user instanceof $model) {
                    return $_user->id;
                } else {
                    throw UserDoesNotExistException::named($user);
                }
            }
        }, $users));
    }

    /**
     * Assign user to group.
     *
     * @param array $users
     *
     * @return $this|bool
     */
    public function assignUser(...$users)
    {
        $users = $this->getCorrectParameter($users);
        $users = $this->convertToUserId($users);
        if ($users->count() == 0) {
            return false;
        }
        $this->users()->syncWithoutDetaching($users);

        return $this;
    }

    /**
     * Remove users from the group.
     *
     * @param array $users
     *
     * @return mixed
     */
    public function removeUser(...$users)
    {
        $users = $this->getCorrectParameter($users);
        $users = $this->getAllUsers($users);
        if ($users->count() == 0) {
            return false;
        }
        $this->users()->detach($users);

        return $this;
    }

    /**
     * Check if the group has any permission of a permission array.
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
     * Check if a group has all specified permissions.
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
     * Scope group to certain user only.
     *
     * @param Builder $query
     * @param $user
     *
     * @return Builder
     */
    public function scopeUser(Builder $query, $user): Builder
    {
        $user = $this->convertToUserModel($user);

        return $query->whereHas('users', function ($query) use ($user) {
            $query->where(function ($query) use ($user) {
                $query->orWhere(config('acl.tables.users').'.id', $user->id);
            });
        });
    }

    /**
     * Revoke all group permissions.
     *
     * @return $this
     */
    public function revokeAllPermissions()
    {
        $this->permissions()->detach();

        return $this;
    }

    /**
     *Assign all system permissions to the specified group.
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

    /**
     * Add all system users to the specified group.
     *
     * @return $this
     */
    public function attachAllUsers()
    {
        $userModel = app(config('acl.models.user'));
        $userModel->all()->map(function ($user) {
            return $this->assignUser([$user]);
        });

        return $this;
    }

    /**
     * Remove all users from the specified group.
     *
     * @return $this
     */
    public function detachAllUsers()
    {
        $this->users()->detach();

        return $this;
    }

    /**
     * Convert user's id, user's name, user's username or user's email to instance of User model.
     *
     * @param $user
     *
     * @return mixed
     */
    private function convertToUserModel($user)
    {
        $userModel = app(config('acl.models.user'));

        $columns = $this->verifyColumns(config('acl.tables.users'));
        $columns = collect($columns)->map(function ($item) {
            if ($item['isset_column']) {
                return $item['column'];
            }
        })->toArray();
        $columns = array_unique($columns);
        $columns = array_filter($columns, 'strlen');

        if ($user instanceof $userModel) {
            return $user;
        } elseif (is_numeric($user)) {
            return $userModel->find($user);
        } elseif (is_string($user)) {
            $user = $userModel->where(function ($query) use ($userModel, $columns, $user) {
                foreach ($columns as $column) {
                    $query->orWhere($column, $user);
                }
            });

            return $user->first();
        } else {
            return;
        }
    }

    /**
     * Verify if a given table has some columns.
     *
     * @param $table
     *
     * @return array
     */
    private function verifyColumns($table)
    {
        return [
            [
                'column'       => 'username',
                'isset_column' => Schema::hasColumn($table, 'username'),
            ],
            [
                'column'       => 'name',
                'isset_column' => Schema::hasColumn($table, 'name'),
            ],
            [
                'column'       => 'email',
                'isset_column' => Schema::hasColumn($table, 'email'),
            ],
        ];
    }

    /**
     * Determine which type of parameter is being used.
     * @param $param
     * @return array
     */
    private function getCorrectParameter($param)
    {
        if (is_array($param[0])) {
            return $param[0];
        }

        return $param;
    }
}
