<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

use Junges\ACL\Exceptions\GroupDoesNotExistException;
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

        $this->assertCount(3, $this->testUser->groups()->get());

        $this->assertTrue($this->testUser->hasGroup(1));
        $this->assertTrue($this->testUser->hasGroup(2));
        $this->assertTrue($this->testUser->hasGroup(3));

        $this->testUser->syncGroups(1, 2);

        $this->assertFalse(
            $this->testUser
                ->groups()
                ->get()
                ->contains('id', 3)
        );
        $this->assertFalse(
            $this->testUser
                ->groups()
                ->get()
                ->contains('id', 4)
        );
        $this->assertTrue($this->testUser->hasGroup(1));
        $this->assertTrue($this->testUser->hasGroup(2));

        $this->assertCount(2, $this->testUser->groups()->get());
    }

    public function test_it_can_assign_groups_which_the_user_does_not_have_previously()
    {
        $this->testUser->assignGroup(1, 2);

        $this->assertCount(2, $this->testUser->groups()->get());

        $this->assertTrue($this->testUser->hasGroup(1));
        $this->assertTrue($this->testUser->hasGroup(2));

        $this->testUser->syncGroups(1, 2, 3);

        $this->assertCount(3, $this->testUser->groups()->get());
    }

    public function test_if_it_can_sync_groups_using_array_as_parameter()
    {
        $this->testUser->assignGroup(1, 2);

        $this->assertCount(2, $this->testUser->groups()->get());

        $this->assertTrue($this->testUser->hasGroup(1));
        $this->assertTrue($this->testUser->hasGroup(2));

        $this->testUser->syncGroups([1, 2, 3]);

        $this->assertCount(3, $this->testUser->groups()->get());
    }

    public function test_it_can_sync_groups_using_group_models()
    {
        $this->testUser->assignGroup(1, 2);

        $this->assertCount(2, $this->testUser->groups()->get());

        $this->assertTrue($this->testUser->hasGroup(1));
        $this->assertTrue($this->testUser->hasGroup(2));

        $this->testUser->syncGroups($this->testUserGroup, $this->testUserGroup2);

        $this->assertCount(2, $this->testUser->groups()->get());
    }

    public function test_it_throws_exception_if_syncing_non_existing_group_id()
    {
        $this->expectException(GroupDoesNotExistException::class);

        $this->testUser->assignGroup(1, 2);

        $this->assertCount(2, $this->testUser->groups()->get());

        $this->assertTrue($this->testUser->hasGroup(1));
        $this->assertTrue($this->testUser->hasGroup(2));

        $this->testUser->syncGroups(1, 2, 123456789);
    }

    public function test_it_throws_exception_if_syncing_non_existing_group_slug()
    {
        $this->expectException(GroupDoesNotExistException::class);

        $this->testUser->assignGroup(1, 2);

        $this->assertCount(2, $this->testUser->groups()->get());

        $this->assertTrue($this->testUser->hasGroup(1));
        $this->assertTrue($this->testUser->hasGroup(2));

        $this->testUser->syncGroups(
            $this->testUserGroup->slug,
            $this->testUserGroup2->slug,
            'some-nonexistent-slug'
        );
    }
}
