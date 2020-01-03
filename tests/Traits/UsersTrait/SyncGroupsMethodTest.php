<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;

class SyncGroupsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_revoke_groups_that_are_not_in_the_sync_groups_function_params()
    {
        $this->testUser->assignGroup(1, 2, 3);

        self::assertCount(3, $this->testUser->groups()->get());

        self::assertTrue($this->testUser->hasGroup(1));
        self::assertTrue($this->testUser->hasGroup(2));
        self::assertTrue($this->testUser->hasGroup(3));

        $this->testUser->syncGroups(1, 2);

        self::assertFalse(
            $this->testUser
                ->groups()
                ->get()
                ->contains('id', 3)
        );
        self::assertFalse(
            $this->testUser
                ->groups()
                ->get()
                ->contains('id', 4)
        );
        self::assertTrue($this->testUser->hasGroup(1));
        self::assertTrue($this->testUser->hasGroup(2));

        self::assertCount(2, $this->testUser->groups()->get());
    }

    public function test_it_can_assign_groups_which_the_user_does_not_have_previously()
    {
        $this->testUser->assignGroup(1, 2);

        self::assertCount(2, $this->testUser->groups()->get());

        self::assertTrue($this->testUser->hasGroup(1));
        self::assertTrue($this->testUser->hasGroup(2));

        $this->testUser->syncGroups(1, 2, 3);

        self::assertCount(3, $this->testUser->groups()->get());
    }

    public function test_if_it_can_sync_groups_using_array_as_parameter()
    {
        $this->testUser->assignGroup(1, 2);

        self::assertCount(2, $this->testUser->groups()->get());

        self::assertTrue($this->testUser->hasGroup(1));
        self::assertTrue($this->testUser->hasGroup(2));

        $this->testUser->syncGroups([1, 2, 3]);

        self::assertCount(3, $this->testUser->groups()->get());
    }
}
