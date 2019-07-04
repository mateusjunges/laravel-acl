<?php

namespace Junges\Tests;

use Illuminate\Support\Facades\Auth;
use Junges\ACL\Tests\TestCase;

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
        $this->testUser->assignPermissions([6, 7]);
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
        Auth::user()->assignPermissions([1]);
        $this->assertTrue(Auth::user()->hasPermissionWithWildcards('*'));
    }

    /**
     * @test
     */
    public function checks_with_star_only_deny_permission_if_the_user_does_not_have__any_permission()
    {
        Auth::login($this->testUser);
        $this->assertFalse(
            Auth::user()->hasPermissionWithWildcards('*')
        );
    }
}
