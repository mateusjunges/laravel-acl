<?php

namespace Junges\ACL\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Junges\ACL\AclRegistrar;
use Junges\ACL\Concerns\HasGroups;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Events\PermissionSaving;
use Junges\ACL\Exceptions\PermissionAlreadyExists;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;
use Junges\ACL\Guard;

class Permission extends Model implements PermissionContract
{
    use SoftDeletes;
    use HasGroups;

    protected $table;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'creating' => PermissionSaving::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('acl.tables.permissions'));
    }

    public function getTable()
    {
        return config('acl.tables.permissions', parent::getTable());
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $attributes['slug'] = $attributes['slug'] ?? Str::slug($attributes['name']);

        $permission = static::getPermission(['slug' => $attributes['slug'], 'guard_name' => $attributes['guard_name']]);

        if ($permission) {
            throw PermissionAlreadyExists::withSlugAndGuard($attributes['slug'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            config('acl.models.group'),
            config('permission.table_names.group_has_permission'),
            AclRegistrar::$pivotPermission,
            AclRegistrar::$pivotGroup
        );
    }

    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_permissions'),
            AclRegistrar::$pivotPermission,
            config('permission.column_names.model_morph_key')
        );
    }

    public static function findByName(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (! $permission) {
            throw PermissionDoesNotExistException::create($name, $guardName);
        }

        return $permission;
    }

    public static function findOrCreate(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (! $permission) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $permission;
    }

    public static function findById(int $id, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['id' => $id, 'guard_name' => $guardName]);

        if (! $permission) {
            throw PermissionDoesNotExistException::withId($id);
        }

        return $permission;
    }

    public function getRouteKeyName(): string
    {
        return config('acl.route_model_binding_keys.permission_model', 'slug');
    }

    protected static function getPermissions(array $params = [], bool $first = false): Collection
    {
        return app(AclRegistrar::class)
            ->setPermissionClass(static::class)
            ->getPermissions($params, $first);
    }

    protected static function getPermission(array $params = []): ?PermissionContract
    {
        return static::getPermissions($params, true)->first();
    }
}
