<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;

class SyncPermissionsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_revoke_permissions_that_are_not_in_the_sync_permissions_function_params()
    {
        $this->testUser->assignPermissions(1, 2, 3, 4);

        self::assertCount(4, $this->testUser->permissions()->get());

        self::assertTrue($this->testUser->hasPermission(1));
        self::assertTrue($this->testUser->hasPermission(2));
        self::assertTrue($this->testUser->hasPermission(3));
        self::assertTrue($this->testUser->hasPermission(4));

        $this->testUser->syncPermissions(1, 2);

        self::assertFalse(
            $this->testUserGroup
                ->permissions()
                ->get()
                ->contains('id', 3)
        );
        self::assertFalse(
            $this->testUserGroup
                ->permissions()
                ->get()
                ->contains('id', 4)
        );
        self::assertTrue($this->testUser->hasPermission(1));
        self::assertTrue($this->testUser->hasPermission(2));

        self::assertCount(2, $this->testUser->permissions()->get());
    }

    public function test_it_can_assign_permissions_which_the_user_does_not_have_previously()
    {
        $this->testUser->assignPermissions(1, 2);

        self::assertCount(2, $this->testUser->permissions()->get());

        self::assertTrue($this->testUser->hasPermission(1));
        self::assertTrue($this->testUser->hasPermission(2));

        $this->testUser->syncPermissions(1, 2, 3, 4);

        self::assertCount(4, $this->testUser->permissions()->get());
    }

    public function test_if_it_can_sync_permissions_using_array_as_parameter()
    {
        $this->testUser->assignPermissions(1, 2);

        self::assertCount(2, $this->testUser->permissions()->get());

        self::assertTrue($this->testUser->hasPermission(1));
        self::assertTrue($this->testUser->hasPermission(2));

        $this->testUser->syncPermissions([1, 2, 3, 4]);

        self::assertCount(4, $this->testUser->permissions()->get());
    }
}
