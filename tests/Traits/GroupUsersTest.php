<?php

namespace Junges\ACLTests\Traits;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class GroupUsersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_a_user_can_be_attached_to_a_group_with_user_model()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser($this->testUser));
    }

    public function test_if_a_user_can_be_attached_to_a_group_with_user_id()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser($this->testUser->id));
    }

    public function test_if_a_user_can_be_attached_to_a_group_with_user_name()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser($this->testUser->name));
    }

    public function test_if_a_user_can_be_attached_to_group_with_mixed_parameters()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser(
            $this->testUser,
            $this->testUser2->name,
            $this->testUser3->id
        ));
    }

    public function test_if_a_user_can_be_attached_to_group_with_array_params()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser([
            $this->testUser,
            $this->testUser2->name,
            $this->testUser3->id,
        ]));
    }
}
