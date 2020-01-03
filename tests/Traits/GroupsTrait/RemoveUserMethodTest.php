<?php

namespace Junges\ACL\Tests\Traits\GroupsTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class RemoveUserMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_remove_a_user_from_a_group()
    {
        $this->testUserGroup->assignUser(1);
        self::assertTrue(User::find(1)->hasGroup($this->testUserGroup));
        $this->testUserGroup->removeUser(1);
        self::assertFalse(User::find(1)->hasGroup($this->testUserGroup));
    }

    public function test_if_it_can_remove_users_from_groups_with_mixed_params()
    {
        $this->testUserGroup->assignUser(1);
        $this->testUserGroup->assignUser(2);
        $this->testUserGroup->assignUser(3);
        self::assertTrue(User::find(1)->hasGroup($this->testUserGroup));
        self::assertTrue(User::find(2)->hasGroup($this->testUserGroup));
        self::assertTrue(User::find(3)->hasGroup($this->testUserGroup));

        $this->testUserGroup->removeUser(1, 'User 2', User::find(3));

        self::assertFalse(User::find(1)->hasGroup($this->testUserGroup));
        self::assertFalse(User::find(2)->hasGroup($this->testUserGroup));
        self::assertFalse(User::find(3)->hasGroup($this->testUserGroup));
    }

    public function test_if_it_can_remove_users_using_array_as_parameter()
    {
        $this->testUserGroup->assignUser(1);
        $this->testUserGroup->assignUser(2);
        $this->testUserGroup->assignUser(3);
        self::assertTrue(User::find(1)->hasGroup($this->testUserGroup));
        self::assertTrue(User::find(2)->hasGroup($this->testUserGroup));
        self::assertTrue(User::find(3)->hasGroup($this->testUserGroup));

        $this->testUserGroup->removeUser([1, 'User 2', User::find(3)]);

        self::assertFalse(User::find(1)->hasGroup($this->testUserGroup));
        self::assertFalse(User::find(2)->hasGroup($this->testUserGroup));
        self::assertFalse(User::find(3)->hasGroup($this->testUserGroup));
    }
}
