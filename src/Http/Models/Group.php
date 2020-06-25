<?php

namespace Junges\ACL\Http\Models;

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
    protected $table;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    protected $dispatchesEvents = [
        'creating' => GroupSaving::class,
    ];

    /**
     * Group constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(Config::get('tables.groups'));
    }

    public function getRouteKeyName()
    {
        return Config::get('route_model_binding_keys.group_model', 'slug');
    }
}
