<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class AssignPermissionsMethodTest extends TestCase
{
    public function test_if_a_permission_can_be_assigned_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions($this->testUserPermission));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission));
    }

    public function test_if_a_permission_can_be_assigned_to_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission2));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission3));
    }

    public function test_if_a_permission_can_be_assigned_to_user_with_permission_ids()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));

        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission2));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission3));
    }

    public function test_it_can_assign_permissions_using_array_as_parameter()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions([
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id,
        ]));

        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission2));
        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission3));
    }
}
