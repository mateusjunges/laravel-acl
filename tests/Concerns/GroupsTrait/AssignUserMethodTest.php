<?php

namespace Junges\ACL\Tests\Concerns\GroupsTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class AssignUserMethodTest extends TestCase
{
    public function test_if_a_user_can_be_attached_to_a_group()
    {
        $group = $this->testUserGroup;
        $user = $this->testUser;
        $group->assignUser($user);
        $this->assertTrue($user->hasGroup($group));
    }

    public function test_if_a_user_can_be_attached_to_group_with_mixed_parameters()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser(
            $this->testUser->id,
            $this->testUser2->name,
            $this->testUser3
        ));
        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));
        $this->assertTrue($this->testUser2->hasGroup($this->testUserGroup));
        $this->assertTrue($this->testUser3->hasGroup($this->testUserGroup));
    }

    public function test_if_a_user_can_be_attached_to_group_with_user_ids()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser(
            $this->testUser->id,
            $this->testUser2->id,
            $this->testUser3->id
        ));

        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));
        $this->assertTrue($this->testUser2->hasGroup($this->testUserGroup));
        $this->assertTrue($this->testUser3->hasGroup($this->testUserGroup));
    }

    public function test_it_can_assign_user_using_array_as_parameter()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser([
            $this->testUser->id,
            $this->testUser2->id,
            $this->testUser3->id,
        ]));

        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));
        $this->assertTrue($this->testUser2->hasGroup($this->testUserGroup));
        $this->assertTrue($this->testUser3->hasGroup($this->testUserGroup));
    }
}
