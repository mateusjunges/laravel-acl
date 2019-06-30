<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\QueryException;

class GroupsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function throws_an_exception_when_the_group_already_exists()
    {
        $this->expectException(QueryException::class);
        Group::create([
             'name' => 'Test Group',
             'slug' => 'test-group',
             'description' => 'This is a test group',
        ]);
        Group::create([
            'name' => 'Test Group',
            'slug' => 'test-group',
            'description' => 'This is a test group',
        ]);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_user_model()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser([$this->testUser]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_user_id()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser([$this->testUser->id]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_user_name()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser([$this->testUser->name]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_mixed_parameters()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignUser(
            [
                $this->testUser,
                $this->testUser2->name,
                $this->testUser3->id,
            ]
        ));
    }

    /**
     * @test
     */
    public function it_can_have_permissions_assigned_by_id()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            [
                $this->testUserPermission->id,
            ]
        ));
    }

    /**
     * @test
     */
    public function it_can_have_permissions_assigned_by_slug()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            [
                $this->testUserPermission->slug,
            ]
        ));
    }

    /**
     * @test
     */
    public function it_can_have_permissions_assigned_by_permission_model()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            [
                $this->testUserPermission,
            ]
        ));
    }

    /**
     * @test
     */
    public function it_can_have_permissions_assigned_by_mixed_parameters()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            [
                $this->testUserPermission,
                $this->testUserPermission2->slug,
                $this->testUserPermission3->id,
            ]
        ));
    }
}
