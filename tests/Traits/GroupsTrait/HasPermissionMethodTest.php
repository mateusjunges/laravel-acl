<?php

namespace Junges\ACL\Tests\Traits\GroupsTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasPermissionMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_the_specified_permission()
    {
        $this->testUserGroup->assignPermissions('edit-posts');
        $this->assertFalse($this->testUserGroup->hasPermission('admin'));
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_any_permissions()
    {
        $this->assertFalse($this->testUserGroup->hasPermission('admin'));
    }

    public function test_if_it_returns_true_if_the_group_has_the_specified_permission_with_id()
    {
        $this->testUserGroup->assignPermissions('admin');
        $this->assertTrue($this->testUserGroup->hasPermission(1));
    }

    public function test_if_it_returns_true_if_the_group_has_the_specified_permission_with_model()
    {
        $this->testUserGroup->assignPermissions('admin');
        $this->assertTrue($this->testUserGroup->hasPermission(Permission::find(1)));
    }

    public function test_if_it_returns_true_if_the_group_has_the_specified_permission_with_slug()
    {
        $this->testUserGroup->assignPermissions('admin');
        $this->assertTrue($this->testUserGroup->hasPermission('admin'));
    }
}
