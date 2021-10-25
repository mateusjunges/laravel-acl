<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

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

        $this->assertTrue($this->testUserGroup->hasPermission(1));
        $this->assertTrue($this->testUserGroup->hasPermission(2));
        $this->assertTrue($this->testUserGroup->hasPermission(3));

        $this->testUser->assignGroup($this->testUserGroup);
        $this->testUser->assignPermissions(4, 5, 6);

        $this->assertTrue($this->testUser->hasPermission(4));
        $this->assertTrue($this->testUser->hasPermission(5));
        $this->assertTrue($this->testUser->hasPermission(6));
        $this->assertCount(6, $this->testUser->getAllPermissions());
    }
}
