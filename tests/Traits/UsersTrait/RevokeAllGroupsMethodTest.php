<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

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

        self::assertTrue($this->testUser2->hasGroup('test-user-group'));

        self::assertInstanceOf(User::class, $this->testUser2->revokeAllGroups());

        self::assertCount(0, $this->testUser2->groups()->get());
    }
}
