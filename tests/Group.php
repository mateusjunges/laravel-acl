<?php

namespace Junges\ACL\Tests;

use Illuminate\Support\Str;
use Junges\ACL\Events\GroupSaving;

class Group extends \Junges\ACL\Models\Group
{
    protected $dates = ['deleted_at'];
    protected $table = 'groups';

    protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'creating' => GroupSaving::class,
    ];

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($this->attributes['name']);
    }

    public function getRouteKeyName(): string
    {
        return config('acl.route_model_binding_keys.group_model', 'slug');
    }
}
