<?php

namespace Junges\ACL\Tests\Traits\GroupsTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasAllPermissionsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_any_permission()
    {
        self::assertFalse($this->testUserGroup->hasAllPermissions(1, 2));
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_all_the_specified_permissions_with_id()
    {
        $this->testUserGroup->assignPermissions(1, 2);
        self::assertFalse($this->testUserGroup->hasAllPermissions(1, 2, 4));
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_all_the_specified_permissions_with_slug()
    {
        $this->testUserGroup->assignPermissions(1, 2);
        self::assertFalse($this->testUserGroup->hasAllPermissions(
            'admin',
            'edit-posts',
            'edit-articles'
        ));
    }

    public function test_if_it_returns_false_if_the_group_does_not_have_all_the_specified_permissions_with_model()
    {
        $this->testUserGroup->assignPermissions(1, 2);
        self::assertFalse($this->testUserGroup->hasAllPermissions(Permission::find(1), Permission::find(2), Permission::find(4)));
    }

    public function test_if_it_returns_true_if_the_group_has_all_the_specified_permissions_with_id()
    {
        $this->testUserGroup->assignPermissions(1, 2);
        self::assertTrue($this->testUserGroup->hasAllPermissions(1, 2));
    }

    public function test_if_it_returns_true_if_the_group_has_all_the_specified_permissions_with_slug()
    {
        $this->testUserGroup->assignPermissions(1, 2);
        self::assertTrue($this->testUserGroup->hasAllPermissions('admin', 'edit-posts'));
    }

    public function test_if_it_returns_true_if_the_group_has_all_the_specified_permissions_with_model()
    {
        $this->testUserGroup->assignPermissions(1, 2);
        self::assertTrue($this->testUserGroup->hasAllPermissions(Permission::find(1), Permission::find(2)));
    }
}
