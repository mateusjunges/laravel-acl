<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class HasAllGroupsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_group()
    {
        self::assertFalse($this->testUser->hasAllGroups(1, 2));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_all_the_specified_groups_with_id()
    {
        $this->testUser->assignGroup(1, 2);
        self::assertFalse($this->testUser->hasAllGroups(1, 2, 4));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_all_the_specified_groups_with_slug()
    {
        $this->testUser->assignGroup(1, 2);
        self::assertFalse($this->testUser->hasAllGroups(
            'test-user-group',
            'test-admin-group',
            'test-user-group-2'
        ));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_all_the_specified_groups_with_model()
    {
        $this->testUser->assignGroup(1, 2);
        self::assertFalse($this->testUser->hasAllGroups(Group::find(1), Group::find(2), Group::find(4)));
    }

    public function test_if_it_returns_true_if_the_user_has_all_the_specified_groups_with_id()
    {
        $this->testUser->assignGroup(1, 2);
        self::assertTrue($this->testUser->hasAllGroups(1, 2));
    }

    public function test_if_it_returns_true_if_the_user_has_all_the_specified_groups_with_slug()
    {
        $this->testUser->assignGroup(1, 2);
        self::assertTrue($this->testUser->hasAllGroups('test-user-group', 'test-admin-group'));
    }

    public function test_if_it_returns_true_if_the_user_has_all_the_specified_groups_with_model()
    {
        $this->testUser->assignGroup(1, 2);
        self::assertTrue($this->testUser->hasAllGroups(Group::find(1), Group::find(2)));
    }
}
