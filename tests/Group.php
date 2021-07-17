<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Eloquent\Model;
use Junges\ACL\Concerns\ACLWildcardsTrait;
use Junges\ACL\Concerns\GroupsTrait;
use Junges\ACL\Events\GroupSaving;

class Group extends Model
{
    use GroupsTrait;
    use ACLWildcardsTrait;

    protected $dates = ['deleted_at'];
    protected $table = 'test_groups';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    protected $dispatchesEvents = [
        'creating' => GroupSaving::class,
    ];

    public function getRouteKeyName()
    {
        return config('acl.route_model_binding_keys.group_model', 'slug');
    }
}
