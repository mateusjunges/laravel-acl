<?php

namespace Junges\ACL\Tests;

class TeamHasPermissionTest extends HasPermissionTest
{
    protected bool $hasTeams = true;
    
    public function testItCanAssignSameAndDifferentPermissionsOnSameUserOnDifferentTeams()
    {
        $this->setPermissionsTeamId(1);
        $this->testUser->load('permissions');
        $this->testUser->assignPermission('edit-articles', 'edit-news');

        $this->setPermissionsTeamId(2);
        $this->testUser->load('permissions');
        $this->testUser->assignPermission('edit-articles', 'edit-blog');

        $this->setPermissionsTeamId(1);
        $this->testUser->load('permissions');
        $this->assertEquals(
            collect(['edit-articles', 'edit-news']),
            $this->testUser->getPermissionNames()->sort()->values()
        );
        $this->assertTrue($this->testUser->hasAllDirectPermissions(['edit-articles', 'edit-news']));
        $this->assertFalse($this->testUser->hasAllDirectPermissions(['edit-articles', 'edit-blog']));

        $this->setPermissionsTeamId(2);
        $this->testUser->load('permissions');
        $this->assertEquals(
            collect(['edit-articles', 'edit-blog']),
            $this->testUser->getPermissionNames()->sort()->values()
        );
        $this->assertTrue($this->testUser->hasAllDirectPermissions(['edit-articles', 'edit-blog']));
        $this->assertFalse($this->testUser->hasAllDirectPermissions(['edit-articles', 'edit-news']));
    }
    
    public function testItCanListAllTheCoupledPermissionsBothDirectlyAssignedAndViaGroupsOnSameUserOnDifferentTeams()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->setPermissionsTeamId(1);
        $this->testUser->load('permissions');
        $this->testUser->assignGroup('testGroup');
        $this->testUser->assignPermission('edit-news');

        $this->setPermissionsTeamId(2);
        $this->testUser->load('permissions');
        $this->testUser->assignGroup('testGroup');
        $this->testUser->assignPermission('edit-blog');

        $this->setPermissionsTeamId(1);
        $this->testUser->load('groups');
        $this->testUser->load('permissions');

        $this->assertEquals(
            collect(['edit-articles', 'edit-news']),
            $this->testUser->getAllPermissions()->pluck('name')->sort()->values()
        );

        $this->setPermissionsTeamId(2);
        $this->testUser->load('groups');
        $this->testUser->load('permissions');

        $this->assertEquals(
            collect(['edit-articles', 'edit-blog']),
            $this->testUser->getAllPermissions()->pluck('name')->sort()->values()
        );
    }
    
    public function testItCanSyncOrRemovePermissionsWithoutDetachOnDifferentTeams()
    {
        $this->setPermissionsTeamId(1);
        $this->testUser->load('permissions');
        $this->testUser->syncPermissions('edit-articles', 'edit-news');

        $this->setPermissionsTeamId(2);
        $this->testUser->load('permissions');
        $this->testUser->syncPermissions('edit-articles', 'edit-blog');

        $this->setPermissionsTeamId(1);
        $this->testUser->load('permissions');

        $this->assertEquals(
            collect(['edit-articles', 'edit-news']),
            $this->testUser->getPermissionNames()->sort()->values()
        );

        $this->testUser->revokePermission('edit-articles');
        $this->assertEquals(
            collect(['edit-news']),
            $this->testUser->getPermissionNames()->sort()->values()
        );

        $this->setPermissionsTeamId(2);
        $this->testUser->load('permissions');
        $this->assertEquals(
            collect(['edit-articles', 'edit-blog']),
            $this->testUser->getPermissionNames()->sort()->values()
        );
    }
}
