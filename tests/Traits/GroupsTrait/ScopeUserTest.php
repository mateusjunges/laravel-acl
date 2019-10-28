<?php

namespace Junges\ACL\Tests\Traits\GroupsTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class ScopeUserTest extends TestCase
{
    public function test_it_returns_a_null_collection_if_no_one_group_is_attached_to_the_given_user()
    {
        self::assertCount(0, Group::user('User 1')->get());
    }

    public function test_it_should_return_only_users_with_the_specified_group()
    {
        $this->testUserGroup->assignUser(1);
        $this->testUserGroup2->assignUser(2);
        self::assertTrue(
            Group::user('User 1')
                ->get()
                ->contains('name', $this->testUserGroup->name)
        );
        self::assertTrue(
            Group::user('User 2')
                ->get()
                ->contains('name', $this->testUserGroup2->name)
        );
        self::assertFalse(
            Group::user('User 3')
                ->get()
                ->contains('name', $this->testAdminGroup->name)
        );
    }
}
