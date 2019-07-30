<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;

class PermissionViaGroupsTest extends TestCase
{
    public function setUp()
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
}
