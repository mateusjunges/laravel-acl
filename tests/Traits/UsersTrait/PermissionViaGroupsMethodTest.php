<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;

class PermissionViaGroupsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_get_the_user_permissions_assigned_via_groups()
    {
        $this->testUserGroup->assignPermissions(1, 2, 3);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));
        $this->assertCount(3, $this->testUser->permissionViaGroups());
    }

    public function test_if_it_returns_only_permission_assigned_via_groups()
    {
        $this->testUserGroup->assignPermissions(1, 2, 3);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->testUser->assignPermissions(4, 5, 6);
        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));
        $this->assertTrue($this->testUser->hasPermission(4));
        $this->assertTrue($this->testUser->hasPermission(5));
        $this->assertTrue($this->testUser->hasPermission(6));
        $this->assertCount(3, $this->testUser->permissionViaGroups());
    }
}
