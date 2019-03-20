<?php
/**
 * Created by PhpStorm.
 * User: mateu
 * Date: 18/03/2019
 * Time: 20:01
 */

namespace MateusJunges\ACL\Traits;


trait PermissionsTrait
{
    /**
     * PermissionsTrait constructor.
     */
    public function __construct()
    {
        $this->table = config('acl.tables.permissions') != ''
            ? config('acl.tables.permissions')
            : 'permissions';
    }

    /**
     * Return all users who has a permission
     * @return mixed
     */
    public function users()
    {
        $model = config('acl.models.user') != ''
            ?  config('acl.models.user')
            : '\App\User::class';
        $table = config('acl.tables.user_has_permissions') != ''
            ? config('acl.tables.user_has_permissions')
            : 'user_has_permissions';
        return $this->belongsToMany($model, $table);
    }

    /**
     * Return all groups which has a permission
     * @return mixed
     */
    public function groups()
    {
        $model = config('acl.models.user') != ''
            ? config('acl.models.group')
            : '\MateusJunges\ACL\Http\Models';
        $table = config('acl.tables.group_has_permissions') != ''
            ? config('acl.tables.group_has_permissions')
            : 'group_has_permissions';
        return $this->belongsToMany($model, $table);
    }
}