<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

use Junges\ACL\Exceptions\PermissionDoesNotExistException;
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

        $this->assertCount(4, $this->testUser->permissions()->get());

        $this->assertTrue($this->testUser->hasPermission(1));
        $this->assertTrue($this->testUser->hasPermission(2));
        $this->assertTrue($this->testUser->hasPermission(3));
        $this->assertTrue($this->testUser->hasPermission(4));

        $this->testUser->syncPermissions(1, 2);

        $this->assertFalse(
            $this->testUserGroup
                ->permissions()
                ->get()
                ->contains('id', 3)
        );
        $this->assertFalse(
            $this->testUserGroup
                ->permissions()
                ->get()
                ->contains('id', 4)
        );
        $this->assertTrue($this->testUser->hasPermission(1));
        $this->assertTrue($this->testUser->hasPermission(2));

        $this->assertCount(2, $this->testUser->permissions()->get());
    }

    public function test_it_can_assign_permissions_which_the_user_does_not_have_previously()
    {
        $this->testUser->assignPermissions(1, 2);

        $this->assertCount(2, $this->testUser->permissions()->get());

        $this->assertTrue($this->testUser->hasPermission(1));
        $this->assertTrue($this->testUser->hasPermission(2));

        $this->testUser->syncPermissions(1, 2, 3, 4);

        $this->assertCount(4, $this->testUser->permissions()->get());
    }

    public function test_if_it_can_sync_permissions_using_array_as_parameter()
    {
        $this->testUser->assignPermissions(1, 2);

        $this->assertCount(2, $this->testUser->permissions()->get());

        $this->assertTrue($this->testUser->hasPermission(1));
        $this->assertTrue($this->testUser->hasPermission(2));

        $this->testUser->syncPermissions([1, 2, 3, 4]);

        $this->assertCount(4, $this->testUser->permissions()->get());
    }

    public function test_it_throws_exception_if_syncing_with_nonexistent_permission_ids()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUser->assignPermissions(1, 2);

        $this->assertCount(2, $this->testUser->permissions()->get());

        $this->assertTrue($this->testUser->hasPermission(1));
        $this->assertTrue($this->testUser->hasPermission(2));

        $this->testUser->syncPermissions([1, 2, 3, 4, 123456789]);
    }

    public function test_it_throws_exception_if_syncing_with_nonexistent_permission_slugs()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUser->assignPermissions(1, 2);

        $this->assertCount(2, $this->testUser->permissions()->get());

        $this->assertTrue($this->testUser->hasPermission(1));
        $this->assertTrue($this->testUser->hasPermission(2));

        $this->testUser->syncPermissions(
            $this->testUserPermission->slug,
            $this->testUserPermission2->slug,
            'some-nonexistent-slug'
        );
    }
}
