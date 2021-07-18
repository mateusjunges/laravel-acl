<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

use Junges\ACL\Tests\TestCase;

class RevokeGroupMethodTest extends TestCase
{
    public function test_it_can_revoke_a_group_from_the_user_using_group_model()
    {
        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));

        $this->testUser->revokeGroup($this->testUserGroup);

        $this->assertFalse($this->testUser->refresh()->hasGroup($this->testUserGroup));
    }

    public function test_it_can_revoke_a_group_from_the_user_using_group_slug()
    {
        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));

        $this->testUser->revokeGroup($this->testUserGroup->slug);

        $this->assertFalse($this->testUser->refresh()->hasGroup($this->testUserGroup));
    }

    public function test_it_can_revoke_a_group_from_the_user_using_group_id()
    {
        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));

        $this->testUser->revokeGroup($this->testUserGroup->id);

        $this->assertFalse($this->testUser->refresh()->hasGroup($this->testUserGroup));
    }
}
