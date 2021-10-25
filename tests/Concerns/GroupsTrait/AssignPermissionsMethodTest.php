<?php

namespace Junges\ACL\Tests\Concerns\GroupsTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class AssignPermissionsMethodTest extends TestCase
{
    public function test_if_a_permission_can_be_assigned_to_a_group()
    {
        $group = $this->testUserGroup;
        $permission = $this->testUserPermission;
        $group->assignPermissions($permission);
        $this->assertTrue($group->hasPermission($permission));
    }

    public function test_if_a_permission_can_be_assigned_to_group_with_mixed_parameters()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));
        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission));
        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission2));
        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission3));
    }

    public function test_if_a_permission_can_be_assigned_to_group_with_permission_ids()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));

        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission));
        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission2));
        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission3));
    }

    public function test_it_can_assign_permissions_using_array_as_parameter()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions([
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
        ]));

        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission));
        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission2));
    }
}
