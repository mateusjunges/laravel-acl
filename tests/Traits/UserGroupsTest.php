<?php

namespace Junges\ACL\Tests\Traits;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class UserGroupsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_revoke_all_user_groups()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokeAllGroups());
        $this->assertCount(0, $this->testUser->groups()->get());
    }

    public function test_if_it_can_add_groups_to_user_with_group_model_instance()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignGroup($this->testUserGroup));
    }

    public function test_if_it_can_add_groups_to_user_with_group_ids()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignGroup($this->testUserGroup->id));
    }

    public function test_if_it_can_add_groups_to_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignGroup(
            $this->testUserGroup->id,
            $this->testAdminGroup->slug,
            $this->testUserGroup2
        ));
    }

    public function test_if_it_can_add_groups_to_user_with_array_parameter()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignGroup([
            $this->testUserGroup->id,
            $this->testAdminGroup->slug,
            $this->testUserGroup2,
        ]));
    }
}
