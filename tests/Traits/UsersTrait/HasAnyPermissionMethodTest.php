<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasAnyPermissionMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permission()
    {
        $this->assertFalse($this->testUser->hasAnyPermission(1));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permission_with_slug()
    {
        $this->assertFalse($this->testUser->hasAnyPermission('admin'));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permissions_with_model()
    {
        $this->assertFalse($this->testUser->hasAnyPermission(Permission::find(1)));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permission_with_mixed_params()
    {
        $this->assertFalse($this->testUser->hasAnyPermission('admin', 2, Permission::find(3)));
    }

    public function test_if_it_returns_false_if_the_user_permission_does_not_match_the_specified_permissions()
    {
        $this->testUser->assignPermissions(2);
        $this->assertFalse($this->testUser->hasAnyPermission('admin'));
    }

    public function test_if_it_returns_true_if_the_user_has_at_least_one_of_the_specified_permissions()
    {
        $this->testUser->assignPermissions(2);
        $this->assertTrue($this->testUser->hasAnyPermission(1, 'edit-posts'));
    }

    public function test_if_it_returns_true_if_the_user_has_the_permission_via_group()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertTrue($this->testUser->hasAnyPermission('edit-posts', 'admin'));
    }
}
