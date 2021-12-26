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

    public function testItCanSycnOrRemoveGroupsWithoutDetachOnDifferentTeams()
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
}
