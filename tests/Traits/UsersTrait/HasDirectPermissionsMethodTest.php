<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasDirectPermissionsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permission()
    {
        $this->assertFalse($this->testUser->hasDirectPermission(1));
    }

    public function test_if_it_returns_false_if_the_user_has_the_permissions_via_group()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertFalse($this->testUser->hasDirectPermission(1));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_permission_with_id()
    {
        $this->testUser->assignPermissions(2);
        $this->assertFalse($this->testUser->hasDirectPermission(1));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_permission_with_slug()
    {
        $this->testUser->assignPermissions(2);
        $this->assertFalse($this->testUser->hasDirectPermission('admin'));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_permission_with_model()
    {
        $this->testUser->assignPermissions(2);
        $this->assertFalse($this->testUser->hasDirectPermission(Permission::find(1)));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_id()
    {
        $this->testUser->assignPermissions(2);
        $this->assertTrue($this->testUser->hasDirectPermission(2));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_slug()
    {
        $this->testUser->assignPermissions(2);
        $this->assertTrue($this->testUser->hasDirectPermission('edit-posts'));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_model()
    {
        $this->testUser->assignPermissions(2);
        $this->assertTrue($this->testUser->hasDirectPermission(Permission::find(2)));
    }
}
