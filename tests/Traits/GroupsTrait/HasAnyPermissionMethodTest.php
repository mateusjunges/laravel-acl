<?php

namespace Junges\Tests\Traits\GroupsTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasAnyPermissionMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_any_permission()
    {
        $this->assertFalse($this->testUserGroup->hasAnyPermission(1));
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_any_permission_with_slug()
    {
        $this->assertFalse($this->testUserGroup->hasAnyPermission('admin'));
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_any_permissions_with_model()
    {
        $this->assertFalse($this->testUserGroup->hasAnyPermission(Permission::find(1)));
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_any_permission_with_mixed_params()
    {
        $this->assertFalse($this->testUserGroup->hasAnyPermission('admin', 2, Permission::find(3)));
    }

    public function test_if_it_returns_false_if_the_group_permission_does_not_match_the_specified_permissions()
    {
        $this->testUserGroup->assignPermissions(2);
        $this->assertFalse($this->testUserGroup->hasAnyPermission('admin'));
    }

    public function test_if_it_returns_true_if_the_group_has_at_least_one_of_the_specified_permissions()
    {
        $this->testUserGroup->assignPermissions(2);
        $this->assertTrue($this->testUserGroup->hasAnyPermission(1, 'edit-posts'));
    }
}
