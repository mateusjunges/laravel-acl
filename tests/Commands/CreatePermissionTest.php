<?php

namespace Junges\ACL\Tests\Commands;

use Junges\ACL\Contracts\Permission;
use Junges\ACL\Tests\TestCase;

class CreatePermissionTest extends TestCase
{
    public function testItCanCreateAPermission()
    {
        $this->artisan('permission:create', ['name' => 'new-permission']);

        $this->assertCount(1, app(Permission::class)->where('name', 'new-permission')->get());
    }

    public function testItCanCreateAPermissionWithSpecificGuard()
    {
        $this->artisan('permission:create', [
            'name' => 'new-permission',
            'guard' => 'api'
        ]);

        $this->assertCount(1, app(Permission::class)
            ->where('name', 'new-permission')
            ->where('guard_name', 'api')
            ->get());
    }

    public function testItCanCreateAPermissionWithoutDuplication()
    {
        $this->artisan('permission:create', ['name' => 'new-permission']);
        $this->artisan('permission:create', ['name' => 'new-permission']);

        $this->assertCount(1, app(Permission::class)->where('name', 'new-permission')->get());
    }
}