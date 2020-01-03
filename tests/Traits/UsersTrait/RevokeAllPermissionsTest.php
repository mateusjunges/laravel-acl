<?php

namespace Junges\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class RevokeAllPermissionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_revoke_all_the_user_permissions()
    {
        self::assertInstanceOf(User::class, $this->testUser->revokeAllPermissions());
        self::assertCount(0, $this->testUser->permissions()->get());
    }

    public function test_if_it_only_removes_direct_associated_permissions()
    {
        $this->testUser->assignPermissions(1, 2, 3);
        $this->testUserGroup->assignPermissions(4, 5, 6);
        $this->testUser->assignGroup($this->testUserGroup);

        $this->testUser->revokeAllPermissions();

        self::assertCount(3, $this->testUser->permissionViaGroups());
        self::assertFalse($this->testUser->hasPermission(1));
        self::assertFalse($this->testUser->hasPermission(2));
        self::assertFalse($this->testUser->hasPermission(3));
        self::assertTrue($this->testUser->hasPermission(4));
        self::assertTrue($this->testUser->hasPermission(5));
        self::assertTrue($this->testUser->hasPermission(6));
    }
}
