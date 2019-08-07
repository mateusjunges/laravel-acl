<?php

namespace Junges\ACL\Tests\Traits\GroupsTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class AssignUserMethodTest extends TestCase
{
    public function test_if_a_user_can_be_attached_to_a_group()
    {
        $group = $this->testUserGroup;
        $user = $this->testUser;
        $group->assignUser($user);
        self::assertTrue($user->hasGroup($group));
    }

    public function test_if_a_user_can_be_attached_to_group_with_mixed_parameters()
    {
        self::assertInstanceOf(Group::class, $this->testUserGroup->assignUser(
            $this->testUser->id,
            $this->testUser2->name,
            $this->testUser3
        ));
        self::assertTrue($this->testUser->hasGroup($this->testUserGroup));
        self::assertTrue($this->testUser2->hasGroup($this->testUserGroup));
        self::assertTrue($this->testUser3->hasGroup($this->testUserGroup));
    }

    public function test_if_a_user_can_be_attached_to_group_with_user_ids()
    {
        self::assertInstanceOf(Group::class, $this->testUserGroup->assignUser(
            $this->testUser->id,
            $this->testUser2->id,
            $this->testUser3->id
        ));

        self::assertTrue($this->testUser->hasGroup($this->testUserGroup));
        self::assertTrue($this->testUser2->hasGroup($this->testUserGroup));
        self::assertTrue($this->testUser3->hasGroup($this->testUserGroup));
    }

    public function test_it_can_assign_user_using_array_as_parameter()
    {
        self::assertInstanceOf(Group::class, $this->testUserGroup->assignUser([
            $this->testUser->id,
            $this->testUser2->id,
            $this->testUser3->id,
        ]));

        self::assertTrue($this->testUser->hasGroup($this->testUserGroup));
        self::assertTrue($this->testUser2->hasGroup($this->testUserGroup));
        self::assertTrue($this->testUser3->hasGroup($this->testUserGroup));
    }
}
