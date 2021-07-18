<?php

namespace Junges\ACL\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Junges\ACL\Concerns\PermissionsTrait;
use Junges\ACL\Events\PermissionSaving;

class Permission extends Model
{
    use SoftDeletes;
    use PermissionsTrait;

    protected $table;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'description', 'slug',
    ];

    protected $dispatchesEvents = [
        'creating' => PermissionSaving::class,
    ];

    /**
     * Permission constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('acl.tables.permissions'));
    }

    public function getRouteKeyName()
    {
        return config('acl.route_model_binding_keys.permission_model', 'slug');
    }
}
