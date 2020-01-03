<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasPermissionMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_the_specified_permission()
    {
        $this->testUser->assignPermissions('edit-posts');
        $this->assertFalse($this->testUser->hasPermission('admin'));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permissions()
    {
        $this->assertFalse($this->testUser->hasPermission('admin'));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_id()
    {
        $this->testUser->assignPermissions('admin');
        $this->assertTrue($this->testUser->hasPermission(1));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_model()
    {
        $this->testUser->assignPermissions('admin');
        $this->assertTrue($this->testUser->hasPermission(Permission::find(1)));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_with_slug()
    {
        $this->testUser->assignPermissions('admin');
        $this->assertTrue($this->testUser->hasPermission('admin'));
    }

    public function test_if_it_returns_true_if_the_user_has_the_specified_permission_via_group()
    {
        $this->testUserGroup->assignPermissions('admin');
        $this->testUser->assignGroup($this->testUserGroup);
        $this->assertTrue($this->testUser->hasPermission(1));
    }
}
