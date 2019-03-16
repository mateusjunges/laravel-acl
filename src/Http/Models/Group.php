<?php

namespace MateusJunges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MateusJunges\ACL\Http\Models\Permission;

class Group extends Model
{
    use SoftDeletes;

    protected $table = 'groups';

    protected $fillable = [
      'name', 'description',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Return all group permissions
     * @param bool $trashed
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions($trashed = false)
    {
        if ($trashed)
            return $this->belongsToMany(Permission::class, 'group_has_permissions');
        return $this->belongsToMany(Permission::class, 'group_has_permissions')
            ->whereNull('group_has_permissions.deleted_at');
    }
    /**
     * Determine if a group has a specific permission
     * @param $permission
     * @param $trashed
     * @return bool
     */
    public function hasPermission($permission, $trashed = false){
        return null !== $this->permissions($trashed)->where('name', $permission)->first();
    }

}
