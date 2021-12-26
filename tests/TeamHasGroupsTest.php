<?php

namespace Junges\ACL\Tests;

use Junges\ACL\Contracts\Group as GroupContract;

class TeamHasGroupsTest extends HasGroupsTest
{
    protected bool $hasTeams = true;

    public function testItCanAssignSameAndDifferentGroupsOnSameUserDifferentTeams()
    {
        app(GroupContract::class)->create(['name' => 'testGroup3']);
        app(GroupContract::class)->create(['name' => 'testGroup3', 'team_id' => 2]);
        app(GroupContract::class)->create(['name' => 'testGroup4', 'team_id' => null]);

        $testGroup3Team1 = app(GroupContract::class)->where(['name' => 'testGroup3', 'team_id' => 1])->first();
        $testGroup3Team2 = app(GroupContract::class)->where(['name' => 'testGroup3', 'team_id' => 2])->first();
        $testGroup4NoTeam = app(GroupContract::class)->where(['name' => 'testGroup4', 'team_id' => null])->first();

        $this->assertNotNull($testGroup3Team1);
        $this->assertNotNull($testGroup4NoTeam);

        $this->setPermissionsTeamId(1);
        $this->testUser->load('groups');
        $this->testUser->assignGroup('testGroup', 'testGroup2');

        $this->setPermissionsTeamId(2);
        $this->testUser->load('groups');
        $this->testUser->assignGroup('testGroup', 'testGroup3');

        $this->setPermissionsTeamId(1);
        $this->testUser->load('groups');

        $this->assertEquals(
            collect(['testGroup', 'testGroup2']),
            $this->testUser->getGroupNames()->sort()->values()
        );
        $this->assertTrue($this->testUser->hasExactlyGroups(['testGroup', 'testGroup2']));

        $this->testUser->assignGroup('testGroup3', 'testGroup4');
        $this->assertTrue($this->testUser->hasExactlyGroups(['testGroup', 'testGroup2', 'testGroup3', 'testGroup4']));
        $this->assertTrue($this->testUser->hasGroup($testGroup3Team1)); //testGroup3 team=1
        $this->assertTrue($this->testUser->hasGroup($testGroup4NoTeam)); // global role team=null

        $this->setPermissionsTeamId(2);
        $this->testUser->load('groups');

        $this->assertEquals(
            collect(['testGroup', 'testGroup3']),
            $this->testUser->getGroupNames()->sort()->values()
        );
        $this->assertTrue($this->testUser->hasExactlyGroups(['testGroup', 'testGroup3']));
        $this->assertTrue($this->testUser->hasGroup($testGroup3Team2)); //testGroup3 team=2
        $this->testUser->assignGroup('testGroup4');
        $this->assertTrue($this->testUser->hasExactlyGroups(['testGroup', 'testGroup3', 'testGroup4']));
        $this->assertTrue($this->testUser->hasGroup($testGroup4NoTeam)); // global role team=null
    }

    public function testItDeletesPivotTablesEntriesWhenDeletingModels()
    {
        $user1 = User::create(['email' => 'user2@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);

        setPermissionsTeamId(1);
        $user1->assignGroup('testGroup');
        $user1->assignPermission('edit-articles');
        $user2->assignGroup('testGroup');
        $user2->assignPermission('edit-articles');

        setPermissionsTeamId(2);
        $user1->assignPermission('edit-news');

        $this->assertDatabaseHas('model_has_permissions', [config('acl.column_names.model_morph_key') => $user1->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.model_morph_key') => $user1->id]);

        $user1->delete();

        setPermissionsTeamId(1);
        $this->assertDatabaseHas('model_has_permissions', [config('acl.column_names.model_morph_key') => $user2->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.model_morph_key') => $user2->id]);
    }

    public function testItCanSyncOrRemoveGroupsWithoutDetachOnDifferentTeams()
    {
        app(GroupContract::class)->create(['name' => 'testGroup3', 'team_id' => 2]);

        $this->setPermissionsTeamId(1);
        $this->testUser->load('groups');
        $this->testUser->syncGroups('testGroup', 'testGroup2');

        $this->setPermissionsTeamId(2);
        $this->testUser->load('groups');
        $this->testUser->syncGroups('testGroup', 'testGroup3');

        $this->setPermissionsTeamId(1);
        $this->testUser->load('groups');

        $this->assertEquals(
            collect(['testGroup', 'testGroup2']),
            $this->testUser->getGroupNames()->sort()->values()
        );

        $this->testUser->revokeGroup('testGroup');
        $this->assertEquals(
            collect(['testGroup2']),
            $this->testUser->getGroupNames()->sort()->values()
        );

        $this->setPermissionsTeamId(2);
        $this->testUser->load('groups');

        $this->assertEquals(
            collect(['testGroup', 'testGroup3']),
            $this->testUser->getGroupNames()->sort()->values()
        );
    }

    public function testItCanScopeUsersOnDifferentTeams()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);

        setPermissionsTeamId(2);
        $user1->assignGroup($this->testUserGroup);
        $user2->assignGroup('testGroup2');

        setPermissionsTeamId(1);
        $user1->assignGroup('testGroup');

        setPermissionsTeamId(2);
        $scopedUsers1Team1 = User::group($this->testUserGroup)->get();
        $scopedUsers2Team1 = User::group(['testGroup', 'testGroup2'])->get();

        $this->assertEquals(1, $scopedUsers1Team1->count());
        $this->assertEquals(2, $scopedUsers2Team1->count());

        setPermissionsTeamId(1);
        $scopedUsers1Team2 = User::group($this->testUserGroup)->get();
        $scopedUsers2Team2 = User::group('testGroup2')->get();

        $this->assertEquals(1, $scopedUsers1Team2->count());
        $this->assertEquals(0, $scopedUsers2Team2->count());
    }
}
