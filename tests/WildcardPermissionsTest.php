<?php

namespace Junges\Tests;

use Junges\ACL\Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class WildcardPermissionsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_can_check_for_permissions_using_wildcards()
    {
        Auth::login($this->testUser);
        $this->testUser->assignPermissions(6, 7);
        $this->assertCount(2, Auth::user()->permissions);
        $this->assertTrue(
            Auth::user()->hasPermissionWithWildcards('admin.*.users')
        );
        $this->assertTrue(
            Auth::user()->haspermissionWithWildcards('admin.*')
        );
    }

    /**
     * @test
     */
    public function it_deny_access_if_user_does_not_have_matching_permissions()
    {
        Auth::login($this->testUser2);
        $this->assertCount(0, Auth::user()->permissions);
        $this->assertFalse(
            Auth::user()->hasPermissionWithWildcards('admin.*')
        );
    }

    /**
     * @test
     */
    public function checks_with_star_only_always_give_permission_if_the_user_has_at_least_one_permission()
    {
        Auth::login($this->testUser);
        Auth::user()->assignPermissions(1);
        $this->assertTrue(Auth::user()->hasPermissionWithWildcards('*'));
    }

    /**
     * @test
     */
    public function checks_with_star_only_deny_permission_if_the_user_does_not_have_any_permission()
    {
        Auth::login($this->testUser);
        $this->assertFalse(
            Auth::user()->hasPermissionWithWildcards('*')
        );
    }

    /**
     * @test
     */
    public function it_can_check_for_group_permissions_using_wildcards()
    {
        $this->testUserGroup->assignPermissions(6, 7);
        $this->assertTrue(
            $this->testUserGroup->hasPermissionWithWildcards('admin.*')
        );
    }

    /**
     * @test
     */
    public function checks_with_star_only_deny_permission_if_the_group_does_not_have_any_permission()
    {
        $this->assertFalse(
            $this->testUserGroup->hasPermissionWithWildcards('*')
        );
    }

    /**
     * @test
     */
    public function checks_with_star_only_always_give_permission_if_the_group_has_at_least_one_permission()
    {
        $this->testUserGroup->assignPermissions(1);
        $this->assertTrue(
            $this->testUserGroup->hasPermissionWithWildcards('*')
        );
    }
}
