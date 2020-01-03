<?php

namespace Junges\Tests\Traits\UsersTrait;

use Junges\ACL\Exceptions\PermissionDoesNotExistException;
use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class ScopePermissionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_returns_a_null_collection_if_no_one_users_has_the_specified_permission()
    {
        self::assertCount(0, User::permission('admin')->get());
    }

    public function test_it_should_return_only_users_with_the_specified_permissions()
    {
        $this->testUser->assignPermissions('edit-posts');
        $this->testUser2->assignPermissions('edit-posts');
        self::assertFalse(
            User::permission('edit-posts')
                ->get()
                ->contains('name', $this->testUser3->name)
        );
        self::assertTrue(
            User::permission('edit-posts')
                ->get()
                ->contains('name', $this->testUser->name)
        );
        self::assertTrue(
            User::permission('edit-posts')
                ->get()
                ->contains('name', $this->testUser2->name)
        );
    }

    public function test_if_it_thrown_an_exception_if_the_group_does_not_exist_using_group_slug()
    {
        $this->expectException(PermissionDoesNotExistException::class);
        User::permission('test-non-existing-permission-slug')->get();
    }

    public function test_if_it_thrown_an_exception_if_the_group_does_not_exist_using_group_id()
    {
        $this->expectException(PermissionDoesNotExistException::class);
        User::permission(987654321)->get();
    }

    public function test_if_it_thrown_an_exception_if_the_group_does_not_exit_using_group_model()
    {
        $this->expectException(PermissionDoesNotExistException::class);
        User::permission(Group::find(987654321))->get();
    }
}
