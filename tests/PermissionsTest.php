<?php

namespace Junges\ACL\Test;

use Illuminate\Database\QueryException;

class PermissionsTest extends TestCase
{
    /**
     * @test
     */
    public function throws_exception_when_creating_duplicate_permissions()
    {
        $this->expectException(QueryException::class);
        Permission::create([
            'slug' => 'test-duplicate-permission-one',
            'name' => 'Test duplicate Permision one',
            'description' => 'This is a test description',
        ]);
        Permission::create([
            'slug' => 'test-duplicate-permission-one',
            'name' => 'Test duplicate Permision one',
            'description' => 'This is a test description',
        ]);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_groups_with_permission_id()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions([1]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_groups_with_permission_slug()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(['edit-posts']));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_groups_with_permission_model()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions([$this->testUserPermission3]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_groups_with_mixed_params()
    {
        $this->assertInstanceOf(Group::class, $this->testUserGroup->assignPermissions(
            [
                $this->testUserPermission,
                $this->testUserPermission2->id,
                $this->testUserPermission3->slug,
            ]
        ));
    }
}
