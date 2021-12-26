<?php

namespace Junges\ACL;

use DateInterval;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Junges\ACL\Contracts\Group;
use Junges\ACL\Contracts\Permission;

class AclRegistrar
{
    protected Repository $cache;
    protected CacheManager $cacheManager;
    protected string $permissionClass;
    protected string $groupClass;
    protected $permissions;
    protected ?int $teamId = null;
    public static string $pivotGroup;
    public static string $pivotPermission;
    public static $cacheExpirationTime;
    public static string $cacheKey;
    public static bool $teams = false;
    public static ?string $teamsKey;
    private ?array $cachedGroups = [];

    public function __construct(CacheManager $cacheManager)
    {
        $this->permissionClass = config('acl.models.permission');
        $this->groupClass = config('acl.models.group');
        $this->cacheManager = $cacheManager;

        $this->initializeCache();
    }

    public function initializeCache()
    {
        self::$cacheExpirationTime = config('acl.cache.expiration_time') ?: DateInterval::createFromDateString('24 hours');

        self::$teams = config('acl.teams', false);
        self::$teamsKey = config('acl.column_names.team_foreign_key');

        self::$cacheKey = config('acl.cache.key');

        self::$pivotGroup = config('acl.column_names.group_pivot_key') ?: 'group_id';
        self::$pivotPermission = config('acl.column_names.permission_pivot_key') ?: 'permission_id';

        $this->cache = $this->getCacheStoreFromConfig();
    }

    public function registerPermissions(): bool
    {
        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'checkPermission')) {
                return $user->checkPermission($ability) ?: null;
            }

            return null;
        });

        return true;
    }

    public function forgetCachedPermissions(): bool
    {
        $this->permissions = null;

        return $this->cache->forget(self::$cacheKey);
    }

    public function forgetPermissionClass()
    {
        $this->permissions = null;
    }

    public function getPermissionClass(): Permission
    {
        return app($this->permissionClass);
    }

    public function setPermissionClass(string $permissionClass): self
    {
        $this->permissionClass = $permissionClass;
        config()->set('acl.models.permission', $permissionClass);
        app()->bind(Permission::class, $permissionClass);

        return $this;
    }

    public function setPermissionsTeamId($id)
    {
        if ($id instanceof Model) {
            $id = $id->getKey();
        }
        $this->teamId = $id;
    }

    public function getPermissionsTeamId()
    {
        return $this->teamId;
    }

    public function getGroupClass(): Group
    {
        return app($this->groupClass);
    }

    public function setGroupClass(string $groupClass): self
    {
        $this->groupClass = $groupClass;
        config()->set('acl.models.group', $groupClass);
        app()->bind(Group::class, $groupClass);

        return $this;
    }

    public function getCacheStore(): Store
    {
        return $this->cache->getStore();
    }

    public function getPermissions(array $params = [], bool $first = false): Collection
    {
        $this->loadPermissions();

        $method = $first ? 'first' : 'filter';

        $permissions = $this->permissions->$method(static function ($permission) use ($params) {
            foreach ($params as $attribute => $value) {
                if ($permission->getAttribute($attribute) != $value) {
                    return false;
                }
            }

            return true;
        });

        if ($first) {
            $permissions = new Collection($permissions ? [$permissions] : []);
        }

        return $permissions;
    }

    private function getHydratedGroup(array $item)
    {
        $groupId = $item['i'] ?? $item['id'];

        if (isset($this->cachedGroups[$groupId])) {
            return $this->cachedGroups[$groupId];
        }

        $groupClass = $this->getGroupClass();
        $groupInstance = new $groupClass();

        return $this->cachedGroups[$groupId] = $groupInstance->newFromBuilder([
            'id' => $groupId,
            'name' => $item['n'] ?? $item['name'],
            'guard_name' => $item['g'] ?? $item['guard_name'],
        ]);
    }

    private function loadPermissions()
    {
        if ($this->permissions !== null) {
            return;
        }

        $this->permissions = $this->cache->remember(self::$cacheKey, self::$cacheExpirationTime, function () {
            return $this->getPermissionClass()->select('id', 'id as i', 'name as n', 'guard_name as g')
               ->with('groups:id,id as i,name as n,guard_name as g')->get()
               ->map(function ($permission) {
                   return $permission->only('i', 'n', 'g') +
                       ['gr' => $permission->groups->map->only('i', 'n', 'g')->all()];
               })->all();
        });

        if (is_array($this->permissions)) {
            $this->permissions = $this->getPermissionClass()::hydrate(
                collect($this->permissions)->map(function ($item) {
                    return ['id' => $item['i'] ?? $item['id'], 'name' => $item['n'] ?? $item['name'], 'guard_name' => $item['g'] ?? $item['guard_name']];
                })->all()
            )
            ->each(function ($permission, $i) {
                $groups = Collection::make($this->permissions[$i]['gr'] ?? $this->permissions[$i]['groups'] ?? [])
                    ->map(fn ($item) => $this->getHydratedGroup($item));

                $permission->setRelation('groups', $groups);
            });

            $this->cachedGroups = [];
        }
    }

    public function getCacheRepository(): Repository
    {
        return $this->cache;
    }

    protected function getCacheStoreFromConfig(): Repository
    {
        $cacheDriver = config('acl.cache.store', 'default');

        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        if (! array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }
}
