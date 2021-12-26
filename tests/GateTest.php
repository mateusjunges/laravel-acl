<?php

namespace Junges\ACL\Tests;

use Illuminate\Contracts\Auth\Access\Gate;

class GateTest extends TestCase
{
    public function testItCanDetermineIfUserDoesntHavePermission()
    {
        $this->assertFalse($this->testUser->can('edit-articles'));
    }

    public function testItAllowsOtherGateBeforeCallbacksToRunIfUserDoesNotHavePermission()
    {
        $this->assertFalse($this->testUser->can('edit-articles'));

        app(Gate::class)->before(fn () => true);

        $this->assertTrue($this->testUser->can('edit-articles'));
    }

    public function testItCanDetermineIfUserHasDirectPermission()
    {
        $this->testUser->assignPermission('edit-articles');

        $this->assertTrue($this->testUser->can('edit-articles'));

        $this->assertFalse($this->testUser->can('non-existing-permission'));

        $this->assertFalse($this->testUser->can('admin-permission'));
    }

    public function testItDeterminesIfUserHasPermissionThroughGroups()
    {
        $this->testUserGroup->assignPermission($this->testUserPermission);

        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission));

        $this->assertTrue($this->testUser->can('edit-articles'));

        $this->assertFalse($this->testUser->can('non-existing-permission'));

        $this->assertFalse($this->testUser->can('admin-permission'));
    }

    public function testItCanDetermineIfUsersWithDifferentGuardHasPermissionUsingGroups()
    {
        $this->testAdminGroup->assignPermission($this->testAdminPermission);

        $this->testAdmin->assignGroup($this->testAdminGroup);

        $this->assertTrue($this->testAdmin->hasPermission($this->testAdminPermission));

        $this->assertTrue($this->testAdmin->can('admin-permission'));

        $this->assertFalse($this->testAdmin->can('non-existing-permission'));

        $this->assertFalse($this->testAdmin->can('edit-articles'));
    }
}