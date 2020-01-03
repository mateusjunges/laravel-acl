<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasAnyGroupMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_group()
    {
        self::assertFalse($this->testUser->hasAnyGroup(1, 2));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_group_with_id()
    {
        $this->testUser->assignGroup(1);
        self::assertFalse($this->testUser->hasAnyGroup(2, 4));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_group_with_slug()
    {
        $this->testUser->assignGroup(1);
        self::assertFalse($this->testUser->hasAnyGroup('edit-posts', 'edit-articles'));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_group_with_model()
    {
        $this->testUser->assignGroup(1);
        self::assertFalse($this->testUser->hasAnyGroup(Permission::find(3), Permission::find(2)));
    }

    public function test_if_it_returns_true_if_the_user_has_at_least_one_of_the_specified_groups_with_id()
    {
        $this->testUser->assignGroup(1);
        self::assertTrue($this->testUser->hasAnyGroup(2, 1));
    }

    public function test_if_it_returns_true_if_the_user_has_at_least_one_of_the_specified_groups_with_slug()
    {
        $this->testUser->assignGroup(1);
        self::assertTrue($this->testUser->hasAnyGroup('test-user-group', 'admin'));
    }

    public function test_if_it_returns_true_if_the_user_has_at_least_one_of_the_specified_groups_with_model()
    {
        $this->testUser->assignGroup(1);
        self::assertFalse($this->testUser->hasAnyGroup(Permission::find(1), Permission::find(2)));
    }
}
