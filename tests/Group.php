<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Eloquent\Model;
use Junges\ACL\Events\GroupSaving;
use Junges\ACL\Traits\ACLWildcardsTrait;
use Junges\ACL\Traits\GroupsTrait;
use Junges\ACL\Helpers\Config;

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
        return Config::get('route_model_binding_keys.group_model', 'slug');
    }
}
