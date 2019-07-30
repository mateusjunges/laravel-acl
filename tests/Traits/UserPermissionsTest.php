<?php

namespace Junges\ACL\Tests\Traits;

use Junges\ACL\Tests\User;
use Junges\ACL\Tests\TestCase;

class UserPermissionsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_if_it_can_revoke_all_the_user_permissions()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokeAllPermissions());
        $this->assertCount(0, $this->testUser->permissions()->get());
    }
}
