<?php

namespace MateusJunges\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Return all permissions which user does not have access
     * @param bool $trashed
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deniedPermissions($trashed = false)
    {
        if($trashed)
            return $this->belongsToMany(Permission::class, 'user_does_not_have_permissions');
        return $this->belongsToMany(Permission::class, 'user_does_not_have_permissions')
            ->whereNull('user_does_not_have_permissions.deleted_at');
    }
    /**
     * Determine if a user has a specific denied permission
     * @param $permission
     * @param bool $trashed
     * @return bool
     */
    public function hasDeniedPermission($permission, $trashed = false)
    {
        return null !== $this->deniedPermissions($trashed)->where('name', $permission)->first();
    }
    /**
     * Return all user permissions
     * Se o parâmetro $trashed for true, busca também nos registros marcados como softDelete
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions($trashed = false){
        if($trashed)
            return $this->belongsToMany(Permission::class, 'user_has_permissions');
        return $this->belongsToMany(Permission::class, 'user_has_permissions')
            ->whereNull('user_has_permissions.deleted_at');
    }
    /**
     * Return all groups assigned to the user
     * @param $trashed
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups($trashed = false){
        if ($trashed)
            return $this->belongsToMany(Group::class, 'user_has_groups');
        return $this->belongsToMany(Group::class, 'user_has_groups')
            ->whereNull('user_has_groups.deleted_at');
    }
    /**
     * Determine if a user has a specific permission
     * @param $trashed
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission, $trashed = false){
        return null !== $this->permissions($trashed)->where('name', '=', $permission)->first();
    }
    /**
     * Determine if a user is an admin
     * @return bool
     */
    public function isAdmin(){
        return $this->hasPermission('admin');
    }
    /**
     * Determine if a user has a specific group
     * @param $trashed
     * @param $group
     * @return bool
     */
    public function hasGroup($group, $trashed = false){
        return null !== $this->groups($trashed)->where('name', $group)->first();
    }
    /**
     * Determine if a user has any group of a group array
     * @param $groups
     * @param $trashed
     * @return bool|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hasAnyGroup($groups, $trashed = false){
        if(is_array($groups))
            return $this->groups($trashed)->whereIn('name', $groups);
        return $this->hasGroup('name', $groups);
    }
}
