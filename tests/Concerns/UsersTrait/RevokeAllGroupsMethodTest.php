<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Tests\User;

class RevokeAllGroupsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_revoke_all_the_user_groups()
    {
        $this->testUser2->assignGroup('test-user-group');

        $this->assertTrue($this->testUser2->hasGroup('test-user-group'));

        $this->assertInstanceOf(User::class, $this->testUser2->revokeAllGroups());

        $this->assertCount(0, $this->testUser2->groups()->get());
    }
}
