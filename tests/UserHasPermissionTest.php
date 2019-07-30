<?php

namespace Junges\ACL\Tests;

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
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions($this->testUserPermission));
    }

    /**
     * @test
     */
    public function can_revoke_permissions_of_user()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokePermissions($this->testUserPermission));
    }

    /**
     * @test
     */
    public function can_give_permission_to_user_with_permission_ids()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));
    }

    /**
     * @test
     */
    public function can_give_permission_to_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignPermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));
    }

    /**
     * @test
     */
    public function can_revoke_permission_from_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->slug,
            $this->testUserPermission3
        ));
    }

    /**
     * @test
     */
    public function can_revoke_permission_from_user_with_permission_ids()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokePermissions(
            $this->testUserPermission->id,
            $this->testUserPermission2->id,
            $this->testUserPermission3->id
        ));
    }

    /**
     * @test
     */
    public function can_revoke_all_user_permissions()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokeAllPermissions());
        $this->assertCount(0, $this->testUser->permissions()->get());
    }

    /**
     * @test
     */
    public function can_revoke_all_user_groups()
    {
        $this->assertInstanceOf(User::class, $this->testUser->revokeAllGroups());
        $this->assertCount(0, $this->testUser->groups()->get());
    }

    /**
     * @test
     */
    public function can_add_groups_to_user_with_group_model_instance()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignGroup($this->testUserGroup));
    }

    /**
     * @test
     */
    public function can_add_groups_to_user_with_group_ids()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignGroup($this->testUserGroup->id));
    }

    /**
     * @test
     */
    public function can_add_groups_to_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(User::class, $this->testUser->assignGroup(
            $this->testUserGroup->id,
            $this->testAdminGroup->slug,
            $this->testUserGroup2
        ));
    }
}
