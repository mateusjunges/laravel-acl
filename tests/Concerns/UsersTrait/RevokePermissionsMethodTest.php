<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class RevokePermissionsMethodTest extends TestCase
{
    public function test_if_a_permission_can_be_revoked_from_user()
    {
        $this->testUser->assignPermission($this->testUserPermission);
        $this->assertInstanceOf(User::class, $this->testUser->revokePermission($this->testUserPermission));
        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission));
    }

    public function test_if_a_permission_can_be_revoked_from_user_with_mixed_parameters()
    {
        $this->testUser->assignPermission($this->testUserPermission);
        $this->testUser->assignPermission($this->testUserPermission2);
        $this->testUser->assignPermission($this->testUserPermission3);

        $this->assertInstanceOf(User::class, $this->testUser->revokePermission(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));

        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission));
        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission2));
        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission3));
    }

    public function test_if_a_permission_can_be_revoked_from_user_with_permission_ids()
    {
        $this->testUser->assignPermission($this->testUserPermission);
        $this->testUser->assignPermission($this->testUserPermission2);
        $this->testUser->assignPermission($this->testUserPermission3);

        $this->assertInstanceOf(User::class, $this->testUser->revokePermission(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));

        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission));
        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission2));
        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission3));
    }

    public function test_if_it_can_revoke_permissions_using_array_as_parameter()
    {
        $this->testUser->assignPermission($this->testUserPermission);
        $this->testUser->assignPermission($this->testUserPermission2);
        $this->testUser->assignPermission($this->testUserPermission3);

        $this->assertInstanceOf(User::class, $this->testUser->revokePermission([
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id,
        ]));

        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission));
        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission2));
        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission3));
    }
}
