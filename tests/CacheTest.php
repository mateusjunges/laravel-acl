<?php

namespace Junges\ACL\Tests;

use Illuminate\Cache\DatabaseStore;
use Illuminate\Support\Facades\DB;
use Junges\ACL\AclRegistrar;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;

class CacheTest extends TestCase
{
    protected int $cacheInitCount = 0;
    protected int $cacheLoadCount = 0;
    protected int $cacheRunCount = 2;
    protected int $cacheRelationsCount = 1;

    protected AclRegistrar $registrar;

    public function setUp(): void
    {
        parent::setUp();

        $this->registrar = app(AclRegistrar::class);

        $this->registrar->forgetCachedPermissions();

        DB::connection()->enableQueryLog();

        if ($this->registrar->getCacheStore() instanceof DatabaseStore) {
            $this->cacheInitCount = 1;
            $this->cacheLoadCount = 1;
        }
    }

    public function testItCanCachePermissions()
    {
        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);
    }

    public function testItFlushesTheCacheWhenCreatingPermissions()
    {
        app(\Junges\ACL\Contracts\Permission::class)->create(['name' => 'new']);

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);
    }

    public function testItFlushesCacheWhenUpdatingPermissions()
    {
        $permission = app(\Junges\ACL\Contracts\Permission::class)->create(['name' => 'new']);

        $permission->name = 'other name';
        $permission->save();

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);
    }

    public function testItFlushesCacheWhenCreatingGroups()
    {
        app(\Junges\ACL\Contracts\Group::class)->create(['name' => 'new']);

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);
    }

    public function testItFlushesCacheWhenUpdatingGroups()
    {
        $group = app(\Junges\ACL\Contracts\Group::class)->create(['name' => 'new']);

        $group->name = 'other name';
        $group->save();

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);
    }

    public function testRevokingUserPermissionShouldNotFlushCache()
    {
        $this->testUser->assignPermission('edit-articles');

        $this->registrar->getPermissions();

        $this->testUser->revokePermission('edit-articles');

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount(0);
    }

    public function testRemovingGroupFromUserShouldNotFlushCache()
    {
        $this->testUser->assignGroup('testGroup');

        $this->registrar->getPermissions();

        $this->testUser->revokeGroup('testGroup');

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount(0);
    }

    public function testItFlushesCacheWhenRemovingPermissionFromGroup()
    {
        $this->testUserPermission->assignGroup('testGroup');

        $this->registrar->getPermissions();

        $this->testUserPermission->revokeGroup('testGroup');

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);
    }

    public function testItFlushesCacheWhenAddingPermissionsToGroups()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);
    }

    public function testItDoesNotFlushCacheWhenCreatingUsers()
    {
        $this->registrar->getPermissions();

        User::create(['email' => 'new']);

        $this->resetQueryCount();

        $this->registrar->getPermissions();

        // should all be in memory, so no init/load required
        $this->assertQueryCount(0);
    }

    public function testHasPermissionUsesCache()
    {
        $this->testUserGroup->assignPermission('edit-articles', 'edit-news', 'Delete News');
        $this->testUser->assignGroup('testGroup');

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermission('edit-articles'));
        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount + $this->cacheRelationsCount);

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermission('edit-news'));
        $this->assertQueryCount(0);

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermission('edit-articles'));
        $this->assertQueryCount(0);

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermission('Delete News'));
        $this->assertQueryCount(0);
    }

    public function testDifferentiatesByGuardName()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUserGroup->assignPermission(['edit-articles', 'web']);
        $this->testUser->assignGroup('testGroup');

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermission('edit-articles', 'web'));
        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount + $this->cacheRelationsCount);

        $this->resetQueryCount();
        $this->assertFalse($this->testUser->hasPermission('edit-articles', 'admin'));
        $this->assertQueryCount(1); // 1 for first lookup of this permission with this guard
    }

    public function testGetAllPermissionsUsesCache()
    {
        $this->testUserGroup->assignPermission($expected = ['edit-articles', 'edit-news']);
        $this->testUser->assignGroup('testGroup');

        $this->resetQueryCount();
        $this->registrar->getPermissions();
        $this->assertQueryCount($this->cacheInitCount + $this->cacheLoadCount + $this->cacheRunCount);

        $this->resetQueryCount();
        $actual = $this->testUser->getAllPermissions()->pluck('name')->sort()->values();
        $this->assertEquals(collect($expected), $actual);

        $this->assertQueryCount(2);
    }

    protected static function assertQueryCount(int $expected)
    {
        self::assertCount($expected, DB::getQueryLog());
    }

    protected function resetQueryCount()
    {
        DB::flushQueryLog();
    }
}
