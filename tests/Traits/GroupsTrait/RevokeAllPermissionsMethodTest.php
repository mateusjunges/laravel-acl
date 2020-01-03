<?php

namespace Junges\ACL\Tests\Traits\GroupsTrait;

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

        self::assertTrue($this->testUserGroup->hasPermission(1));
        self::assertTrue($this->testUserGroup->hasPermission(2));
        self::assertTrue($this->testUserGroup->hasPermission(3));

        self::assertInstanceOf(Group::class, $this->testUserGroup->revokeAllPermissions());

        self::assertCount(0, $this->testUserGroup->permissions()->get());
    }
}
