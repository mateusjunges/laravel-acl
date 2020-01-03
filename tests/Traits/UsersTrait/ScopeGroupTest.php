<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Exceptions\GroupDoesNotExistException;
use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class ScopeGroupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_returns_a_null_collection_if_no_one_users_has_the_specified_group()
    {
        self::assertCount(0, User::group('test-user-group')->get());
    }

    public function test_it_should_return_only_users_with_the_specified_group()
    {
        $this->testUser->assignGroup('test-user-group');
        $this->testUser2->assignGroup('test-user-group');
        $this->testUser3->assignGroup($this->testUserGroup2);
        self::assertFalse(
            User::group('test-user-group')
                ->get()
                ->contains('name', $this->testUser3->name)
        );
        self::assertTrue(
            User::group('test-user-group')
                ->get()
                ->contains('name', $this->testUser->name)
        );
        self::assertTrue(
            User::group('test-user-group')
                ->get()
                ->contains('name', $this->testUser2->name)
        );
        self::assertCount(2, User::group('test-user-group')->get());
        self::assertCount(1, User::group($this->testUserGroup2)->get());
    }

    public function test_if_it_thrown_an_exception_if_the_group_does_not_exist_using_group_slug()
    {
        $this->expectException(GroupDoesNotExistException::class);
        User::group('test-non-existing-group-slug')->get();
    }

    public function test_if_it_thrown_an_exception_if_the_group_does_not_exist_using_group_id()
    {
        $this->expectException(GroupDoesNotExistException::class);
        User::group(987654321)->get();
    }

    public function test_if_it_thrown_an_exception_if_the_group_does_not_exit_using_group_model()
    {
        $this->expectException(GroupDoesNotExistException::class);
        User::group(Group::find(987654321))->get();
    }
}
