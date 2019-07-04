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
        $this->assertCount(2, $this->testUser->permissions);
        $this->assertTrue(
            $this->testUser->hasPermissionWithWildcards('admin.*.users')
        );
    }
}
