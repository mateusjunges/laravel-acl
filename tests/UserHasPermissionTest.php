<?php

namespace Junges\ACL\Test;

class UserHasPermissionTest extends TestCase
{
    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function can_give_permission_to_user()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions([$this->testUserPermission]));
    }

    /**
     * @test
     */
    public function can_revoke_permissions_of_user()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokePermissions([$this->testUserPermission]));
    }
}
