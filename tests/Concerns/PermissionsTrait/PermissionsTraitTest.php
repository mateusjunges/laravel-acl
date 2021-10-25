<?php

namespace Junges\ACL\Tests\Concerns\PermissionsTrait;

use Junges\ACL\Exceptions\UserDoesNotExistException;
use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class PermissionsTraitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->testUser->assignPermissions($this->testUserPermission);
        $this->testUser2->assignPermissions($this->testUserPermission);
    }

    public function test_it_can_get_all_users_who_has_a_permission()
    {
        $users = $this->testUserPermission->users()->get();

        $this->assertCount(2, $users);
    }

    public function test_user_scope_can_get_only_permissions_belonging_to_the_given_user_via_name()
    {
        $permissions = Permission::user($this->testUser->name)->get();

        $this->assertCount(1, $permissions);

        $this->testUser->assignPermissions($this->testUserPermission2);

        $permissions = Permission::user($this->testUser->name)->get();

        $this->assertCount(2, $permissions);
    }

    public function test_user_scope_can_get_only_permissions_belonging_to_the_given_user_via_model_instance()
    {
        $permissions = Permission::user($this->testUser)->get();

        $this->assertCount(1, $permissions);

        $this->testUser->assignPermissions($this->testUserPermission2);

        $permissions = Permission::user($this->testUser->name)->get();

        $this->assertCount(2, $permissions);
    }

    public function test_user_scope_can_get_only_permissions_belonging_to_the_given_user_via_id()
    {
        $permissions = Permission::user($this->testUser->id)->get();

        $this->assertCount(1, $permissions);

        $this->testUser->assignPermissions($this->testUserPermission2);

        $permissions = Permission::user($this->testUser->name)->get();

        $this->assertCount(2, $permissions);
    }

    public function test_user_scope_throws_exception_if_it_cant_find_the_user()
    {
        $this->expectException(UserDoesNotExistException::class);

        Permission::user('Non existing user')->get();
    }

    public function test_user_scope_throws_exception_if_the_user_does_not_exist_with_the_given_id()
    {
        $this->expectException(UserDoesNotExistException::class);

        Permission::user(123456789)->get();
    }

    public function test_it_throws_exception_if_using_invalid_arguments_with_the_user_scope()
    {
        $this->expectException(UserDoesNotExistException::class);

        Permission::user([123456789])->get();
    }
}
