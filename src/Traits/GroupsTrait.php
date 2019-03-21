<?php

namespace MateusJunges\ACL\Traits;


use Illuminate\Database\Eloquent\SoftDeletes;

trait GroupsTrait
{
    use SoftDeletes;
    /**
     * Return all group permissions
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(config('acl.models.permission'), config('acl.tables.group_has_permissions'));
    }

    /**
     * Used only to fill the group form
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return null !== $this->permissions->where('id', $permission)->first();
    }

    /**
     * Return all users who has a group
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(config('acl.models.User'), config('acl.tables.user_has_groups'));
    }

    /**
     * Add permissions to a group
     * @param array $permissions
     * @return $this|bool
     */
    public function givePermissions(array $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions->count() == 0)
            return false;
        $this->permissions()->syncWithoutDetaching($permissions);
        return $this;
    }

    /**
     * Remove group permissions
     * @param array $permissions
     * @return $this
     */
    public function revokePermissions(array $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    /**
     * Retrive a permission model for each one of the permissions id array
     * @param array $permissions
     * @return mixed
     */
    protected function getAllPermissions(array $permissions)
    {
        $model = app(config('acl.models.permission'));
        return $model->whereIn('id', $permissions)->get();
    }

}