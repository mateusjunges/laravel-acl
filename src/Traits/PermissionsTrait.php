<?php

namespace Junges\ACL\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\Helpers\Config;

trait PermissionsTrait
{
    /**
     * Return all users who has a permission.
     *
     * @return mixed
     */
    public function users()
    {
        $model = Config::get('models.user') != ''
            ? Config::get('models.user')
            : '\App\User::class';
        $table = Config::get('tables.user_has_permissions') != ''
            ? Config::get('tables.user_has_permissions')
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
        $model = Config::get('models.user') != ''
            ? Config::get('models.group')
            : '\Junges\ACL\Http\Models';
        $table = Config::get('tables.group_has_permissions') != ''
            ? Config::get('tables.group_has_permissions')
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
                $query->orWhere(Config::get('tables.users').'.id', $user->id);
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
        $userModel = app(Config::get('models.user'));

        $columns = $this->verifyColumns(Config::get('tables.users'));
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
}
