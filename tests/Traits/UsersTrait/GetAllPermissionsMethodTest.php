<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;

class GetAllPermissionsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_return_all_user_permissions_even_if_via_group()
    {
        $this->testUserGroup->assignPermissions(1, 2, 3);
        self::assertTrue($this->testUserGroup->hasPermission(1));
        self::assertTrue($this->testUserGroup->hasPermission(2));
        self::assertTrue($this->testUserGroup->hasPermission(3));
        $this->testUser->assignGroup($this->testUserGroup);
        $this->testUser->assignPermissions(4, 5, 6);
        self::assertTrue($this->testUser->hasPermission(4));
        self::assertTrue($this->testUser->hasPermission(5));
        self::assertTrue($this->testUser->hasPermission(6));
        self::assertCount(6, $this->testUser->getAllPermissions());
    }
}
