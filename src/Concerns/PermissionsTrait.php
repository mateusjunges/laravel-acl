<?php

namespace Junges\ACL\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\Exceptions\UserDoesNotExistException;

trait PermissionsTrait
{
    /**
     * Return all users who has a permission.
     *
     * @return mixed
     */
    public function users()
    {
        $model = config('acl.models.user') != ''
            ? config('acl.models.user')
            : '\App\User::class';

        $table = config('acl.tables.user_has_permissions') != ''
            ? config('acl.tables.user_has_permissions')
            : 'user_has_permissions';

        return $this->belongsToMany($model, $table);
    }

    /**
     * Return all groups which has a permission.
     *
     * @return mixed
     */
    public function groups()
    {
        $model = config('acl.models.user') != ''
            ? config('acl.models.group')
            : '\Junges\ACL\Http\Models';
        $table = config('acl.tables.group_has_permissions') != ''
            ? config('acl.tables.group_has_permissions')
            : 'group_has_permissions';

        return $this->belongsToMany($model, $table);
    }

    /**
     * Scope permissions model queries certain user only.
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
        }

        if (is_numeric($user)) {
            $user = $userModel->find($user);

            if (! $user) {
                throw new UserDoesNotExistException();
            }

            return $user;
        }

        if (is_string($user)) {
            $user = $userModel->where(function ($query) use ($columns, $user) {
                foreach ($columns as $column) {
                    $query->orWhere($column, $user);
                }
            });

            $user = $user->first();

            if (! $user) {
                throw new UserDoesNotExistException();
            }

            return $user;
        }

        throw new UserDoesNotExistException();
    }

    /**
     * Verify if a given table has some columns.
     *
     * @param $table
     *
     * @return array
     */
    private function verifyColumns($table): array
    {
        return [
            [
                'column' => 'username',
                'isset_column' => Schema::hasColumn($table, 'username'),
            ],
            [
                'column' => 'name',
                'isset_column' => Schema::hasColumn($table, 'name'),
            ],
            [
                'column' => 'email',
                'isset_column' => Schema::hasColumn($table, 'email'),
            ],
        ];
    }
}
