<?php

namespace Junges\ACL\Tests\Traits;

use Junges\ACL\Tests\User;
use Junges\ACL\Tests\TestCase;

class UserPermissionsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_if_a_permission_can_be_assigned_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions($this->testUserPermission));
    }

    public function test_if_a_permission_can_be_revoked_from_user()
    {
        $this->testUser->assignPermissions($this->testUserPermission);
        $this->assertInstanceOf(User::class, $this->testUser->revokePermissions($this->testUserPermission));
    }

    public function test_if_a_permission_can_be_assigned_to_user_with_permission_ids()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));
    }

    public function test_if_a_permission_can_be_assigned_to_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));
    }

    public function test_if_a_permission_can_be_revoked_from_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));
    }

    public function test_if_a_permission_can_be_revoked_from_user_with_permission_ids()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));
    }

    public function test_if_it_can_revoke_all_the_user_permissions()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokeAllPermissions());
        $this->assertCount(0, $this->testUser->permissions()->get());
    }
}
