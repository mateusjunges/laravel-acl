<?php

namespace Junges\ACL\Tests\Traits;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class GroupPermissionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_a_group_can_have_permissions_assigned_by_id()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testAdminPermission->id
        ));
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(2));
    }

    public function test_if_a_group_can_have_permissions_assigned_by_slug()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testAdminPermission->slug
        ));
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions('edit-posts'));
    }

    public function test_if_a_group_can_have_permissions_assigned_by_permission_model()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testUserPermission
        ));
    }

    public function test_if_a_group_can_have_permissions_assigned_by_mixed_parameters()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            $this->testUserPermission,
            $this->testUserPermission2->slug,
            $this->testUserPermission3->id
        ));
    }
}
