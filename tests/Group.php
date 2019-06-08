<?php

namespace Junges\ACL\Test;

use Junges\ACL\Events\GroupSaving;
use Junges\ACL\Traits\GroupsTrait;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use GroupsTrait;

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
}
