<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class RevokePermissionsMethodTest extends TestCase
{
    public function test_if_a_permission_can_be_revoked_from_user()
    {
        $this->testUser->assignPermissions($this->testUserPermission);
        self::assertInstanceOf(User::class, $this->testUser->revokePermissions($this->testUserPermission));
        self::assertFalse($this->testUser->hasPermission($this->testUserPermission));
    }

    public function test_if_a_permission_can_be_revoked_from_user_with_mixed_parameters()
    {
        $this->testUser->assignPermissions($this->testUserPermission);
        $this->testUser->assignPermissions($this->testUserPermission2);
        $this->testUser->assignPermissions($this->testUserPermission3);

        self::assertInstanceOf(User::class, $this->testUser->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));

        self::assertFalse($this->testUser->hasPermission($this->testUserPermission));
        self::assertFalse($this->testUser->hasPermission($this->testUserPermission2));
        self::assertFalse($this->testUser->hasPermission($this->testUserPermission3));
    }

    public function test_if_a_permission_can_be_revoked_from_user_with_permission_ids()
    {
        $this->testUser->assignPermissions($this->testUserPermission);
        $this->testUser->assignPermissions($this->testUserPermission2);
        $this->testUser->assignPermissions($this->testUserPermission3);

        self::assertInstanceOf(User::class, $this->testUser->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));

        self::assertFalse($this->testUser->hasPermission($this->testUserPermission));
        self::assertFalse($this->testUser->hasPermission($this->testUserPermission2));
        self::assertFalse($this->testUser->hasPermission($this->testUserPermission3));
    }

    public function test_if_it_can_revoke_permissions_using_array_as_parameter()
    {
        $this->testUser->assignPermissions($this->testUserPermission);
        $this->testUser->assignPermissions($this->testUserPermission2);
        $this->testUser->assignPermissions($this->testUserPermission3);

        self::assertInstanceOf(User::class, $this->testUser->revokePermissions([
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id,
        ]));

        self::assertFalse($this->testUser->hasPermission($this->testUserPermission));
        self::assertFalse($this->testUser->hasPermission($this->testUserPermission2));
        self::assertFalse($this->testUser->hasPermission($this->testUserPermission3));
    }
}
