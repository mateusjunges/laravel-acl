<?php

namespace Junges\ACL\Tests;

use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Exceptions\PermissionAlreadyExists;

class PermissionTest extends TestCase
{
    public function testItThrowsAnExceptionWhenThePermissionAlreadyExists()
    {
        $this->expectException(PermissionAlreadyExists::class);

        app(PermissionContract::class)->create(['name' => 'test-permission']);
        app(PermissionContract::class)->create(['name' => 'test-permission']);
    }

    public function testItBelongsToAGuard()
    {
        $permission = app(Permission::class)->create(['name' => 'can-edit', 'guard_name' => 'admin']);

        $this->assertEquals('admin', $permission->guard_name);
    }

    public function testItBelongsToDefaultGuardByDefault()
    {
        $this->assertEquals(
            $this->app['config']->get('auth.defaults.guard'),
            $this->testUserPermission->guard_name
        );
    }

    public function testItHasUserModelsOfTheRightClass()
    {
        $this->testAdmin->assignPermission($this->testAdminPermission);

        $this->testUser->assignPermission($this->testUserPermission);

        $this->assertCount(1, $this->testUserPermission->users);
        $this->assertTrue($this->testUserPermission->users->first()->is($this->testUser));
        $this->assertInstanceOf(User::class, $this->testUserPermission->users->first());
    }

    public function testItIsRetrievableById()
    {
        $permission_by_id = app(PermissionContract::class)->findById($this->testUserPermission->id);

        $this->assertEquals($this->testUserPermission->id, $permission_by_id->id);
    }

    public function testItIsRetrievableByName()
    {
        $permission_by_name= app(PermissionContract::class)->findByName($this->testUserPermission->name);

        $this->assertEquals($this->testUserPermission->name, $permission_by_name->name);
    }
}