<?php

namespace Junges\ACL\Tests\Concerns\GroupsTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class RevokeAllPermissionsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_revoke_all_the_group_permissions()
    {
        $this->testUserGroup->assignPermissions(1, 2, 3);

        $this->assertTrue($this->testUserGroup->hasPermission(1));
        $this->assertTrue($this->testUserGroup->hasPermission(2));
        $this->assertTrue($this->testUserGroup->hasPermission(3));

        $this->assertInstanceOf(Group::class, $this->testUserGroup->revokeAllPermissions());

        $this->assertCount(0, $this->testUserGroup->permissions()->get());
    }
}
