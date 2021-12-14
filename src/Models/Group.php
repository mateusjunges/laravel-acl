<?php

namespace Junges\ACL\Models;

use Illuminate\Database\Eloquent\Model;
use Junges\ACL\Concerns\ACLWildcardsTrait;
use Junges\ACL\Concerns\GroupsTrait;
use Junges\ACL\Events\GroupSaving;

class Group extends Model
{
    use GroupsTrait;
    use ACLWildcardsTrait;

    protected $dates = ['deleted_at'];
    protected $table;

    protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'creating' => GroupSaving::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('acl.tables.groups'));
    }

    public function getRouteKeyName(): string
    {
        return config('acl.route_model_binding_keys.group_model', 'slug');
    }
}
