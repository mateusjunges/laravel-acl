<?php

namespace Junges\ACL\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;
use Junges\ACL\Traits\GroupsTrait;

class Group extends Model
{
    use GroupsTrait;

    protected $dates = ['deleted_at'];
    protected $table = 'groups';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    public static function create(array $attributes = [])
    {
        if(static::where('slug', $attributes['slug'])->orWhere('name', $attributes['name'])->first()){
            throw GroupAlreadyExistsException::create();
        }
        return parent::create($attributes);
    }
}
