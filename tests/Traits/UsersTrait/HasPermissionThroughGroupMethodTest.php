<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasPermissionThroughGroupMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permissions()
    {
        $this->assertFalse($this->testUser->hasPermissionThroughGroup(1));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_permission_with_id()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertFalse($this->testUser->hasPermissionThroughGroup(2));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_permission_with_slug()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertFalse($this->testUser->hasPermissionThroughGroup('edit-posts'));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_permission_with_model()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertFalse($this->testUser->hasPermissionThroughGroup(Permission::find(2)));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_id()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertTrue($this->testUser->hasPermissionThroughGroup(1));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_slug()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertTrue($this->testUser->hasPermissionThroughGroup('admin'));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_model()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertTrue($this->testUser->hasPermissionThroughGroup(Permission::find(1)));
    }

    public function test_if_it_returns_false_if_the_user_has_the_permission_directly_associated()
    {
        $this->testUser->assignPermissions(1);
        $this->assertFalse($this->testUser->hasPermissionThroughGroup(Permission::find(1)));
        $this->assertFalse($this->testUser->hasPermissionThroughGroup('admin'));
        $this->assertFalse($this->testUser->hasPermissionThroughGroup(Permission::find(1)));
    }
}
