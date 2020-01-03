<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class HasAllPermissionsMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_any_permission()
    {
        self::assertFalse($this->testUser->hasAllPermissions(1, 2));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_all_the_specified_permissions_with_id()
    {
        $this->testUser->assignPermissions(1, 2);
        self::assertFalse($this->testUser->hasAllPermissions(1, 2, 4));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_all_the_specified_permissions_with_slug()
    {
        $this->testUser->assignPermissions(1, 2);
        self::assertFalse($this->testUser->hasAllPermissions(
            'admin',
            'edit-posts',
            'edit-articles'
        ));
    }

    public function test_if_it_returns_false_if_the_user_does_not_have_all_the_specified_permissions_with_model()
    {
        $this->testUser->assignPermissions(1, 2);
        self::assertFalse($this->testUser->hasAllPermissions(Permission::find(1), Permission::find(2), Permission::find(4)));
    }

    public function test_if_it_returns_true_if_the_user_has_all_the_specified_permissions_with_id()
    {
        $this->testUser->assignPermissions(1, 2);
        self::assertTrue($this->testUser->hasAllPermissions(1, 2));
    }

    public function test_if_it_returns_true_if_the_user_has_all_the_specified_permissions_with_slug()
    {
        $this->testUser->assignPermissions(1, 2);
        self::assertTrue($this->testUser->hasAllPermissions('admin', 'edit-posts'));
    }

    public function test_if_it_returns_true_if_the_user_has_all_the_specified_permissions_with_model()
    {
        $this->testUser->assignPermissions(1, 2);
        self::assertTrue($this->testUser->hasAllPermissions(Permission::find(1), Permission::find(2)));
    }
}
