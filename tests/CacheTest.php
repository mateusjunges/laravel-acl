<?php

namespace Junges\ACL\Tests;

use Illuminate\Cache\DatabaseStore;
use Illuminate\Support\Facades\DB;
use Junges\ACL\AclRegistrar;

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

    protected static function assertQueryCount(int $expected)
    {
        self::assertCount($expected, DB::getQueryLog());
    }

    protected function resetQueryCount()
    {
        DB::flushQueryLog();
    }
}
