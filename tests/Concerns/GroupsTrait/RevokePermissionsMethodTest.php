<?php

namespace Junges\ACL\Tests\Concerns\GroupsTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class RevokePermissionsMethodTest extends TestCase
{
    public function test_if_a_permission_can_be_revoked_from_group()
    {
        $this->testUserGroup->assignPermissions($this->testUserPermission);
        $this->assertInstanceOf(Group::class, $this->testUserGroup->revokePermissions($this->testUserPermission));
        $this->assertFalse($this->testUserGroup->hasPermission($this->testUserPermission));
    }

    public function test_if_a_permission_can_be_revoked_from_group_with_mixed_parameters()
    {
        $this->testUserGroup->assignPermissions($this->testUserPermission);
        $this->testUserGroup->assignPermissions($this->testUserPermission2);
        $this->testUserGroup->assignPermissions($this->testUserPermission3);

        $this->assertInstanceOf(Group::class, $this->testUserGroup->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));

        $this->assertFalse($this->testUserGroup->hasPermission($this->testUserPermission));
        $this->assertFalse($this->testUserGroup->hasPermission($this->testUserPermission2));
        $this->assertFalse($this->testUserGroup->hasPermission($this->testUserPermission3));
    }

    public function test_if_a_permission_can_be_revoked_from_group_with_permission_ids()
    {
        $this->testUserGroup->assignPermissions($this->testUserPermission);
        $this->testUserGroup->assignPermissions($this->testUserPermission2);
        $this->testUserGroup->assignPermissions($this->testUserPermission3);

        $this->assertInstanceOf(Group::class, $this->testUserGroup->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));

        $this->assertFalse($this->testUserGroup->hasPermission($this->testUserPermission));
        $this->assertFalse($this->testUserGroup->hasPermission($this->testUserPermission2));
        $this->assertFalse($this->testUserGroup->hasPermission($this->testUserPermission3));
    }
}
