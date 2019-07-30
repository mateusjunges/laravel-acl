<?php

namespace Junges\ACL\Tests\Traits\GroupsTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\Group;

class AssignPermissionsMethodTest extends TestCase
{
    public function test_if_a_permission_can_be_assigned_to_a_group()
    {
        $group = $this->testUserGroup;
        $permission = $this->testUserPermission;
        $group->assignPermissions($permission);
        self::assertTrue($group->hasPermission($permission));
    }

    public function test_if_a_permission_can_be_assigned_to_group_with_mixed_parameters()
    {
        self::assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));
        self::assertTrue($this->testUserGroup->hasPermission($this->testUserPermission));
        self::assertTrue($this->testUserGroup->hasPermission($this->testUserPermission2));
        self::assertTrue($this->testUserGroup->hasPermission($this->testUserPermission3));
    }

    public function test_if_a_permission_can_be_assigned_to_group_with_permission_ids()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));

        self::assertTrue($this->testUserGroup->hasPermission($this->testUserPermission));
        self::assertTrue($this->testUserGroup->hasPermission($this->testUserPermission2));
        self::assertTrue($this->testUserGroup->hasPermission($this->testUserPermission3));
    }
}
