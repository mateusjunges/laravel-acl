<?php

namespace Junges\ACL\Tests;

use Illuminate\Database\Schema\Blueprint;
use Junges\ACL\Contracts\Group as GroupContract;

class TeamHasGroupsTraitTest extends TestCase
{
    /** @var bool */
    protected bool $hasTeams = true;
    protected Team $testTeam1;
    protected Team $testTeam2;

    protected function configureDatabase($app)
    {
        parent::configureDatabase($app);

        $app['db']->connection()->getSchemaBuilder()->create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
        });
        $this->testTeam1 = Team::create([]);
        $this->testTeam2 = Team::create([]);
    }

    public function testItDeletesPivotTableEntriesWhenDeletingTeams()
    {
        $user = User::create(['email' => 'user@test.com']);

        setPermissionsTeamId($this->testTeam1->id);
        $user->assignGroup('testGroup');
        $user->assignPermission('edit-articles');

        setPermissionsTeamId($this->testTeam2->id);
        $user->assignGroup('testGroup2');
        $user->assignPermission('edit-news');

        setPermissionsTeamId($this->testTeam1->id);
        $this->assertDatabaseHas('model_has_permissions', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);

        setPermissionsTeamId($this->testTeam2->id);
        $this->testTeam1->delete();

        setPermissionsTeamId($this->testTeam1->id);
        $this->assertDatabaseMissing('model_has_permissions', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);
        $this->assertDatabaseMissing('model_has_groups', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);

        setPermissionsTeamId($this->testTeam2->id);
        $this->assertDatabaseHas('model_has_permissions', [config('acl.column_names.team_foreign_key') => $this->testTeam2->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.team_foreign_key') => $this->testTeam2->id]);
    }

    public function testItDeletesSpecificGroupEntriesWhenDeletingTeams()
    {
        app(GroupContract::class)->create(['name' => 'testGroup3', 'team_id' => $this->testTeam1->id]);
        app(GroupContract::class)->create(['name' => 'testGroup3', 'team_id' => $this->testTeam2->id]);

        $user = User::create(['email' => 'user@test.com']);
        $user->assignGroup('testGroup3');

        setPermissionsTeamId($this->testTeam2->id);

        $user->assignGroup('testGroup3');

        $this->assertDatabaseHas('groups', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);
        $this->assertDatabaseHas('groups', [config('acl.column_names.team_foreign_key') => $this->testTeam2->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.team_foreign_key') => $this->testTeam2->id]);

        $this->testTeam1->delete();

        $this->assertDatabaseMissing('groups', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);
        $this->assertDatabaseMissing('model_has_groups', [config('acl.column_names.team_foreign_key') => $this->testTeam1->id]);
        $this->assertDatabaseHas('groups', [config('acl.column_names.team_foreign_key') => $this->testTeam2->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.team_foreign_key') => $this->testTeam2->id]);
    }

    public function testItDoesNotDetachGroupsWhenSoftDeleting()
    {
        $user = User::create(['email' => 'test@example.com']);
        setPermissionsTeamId($this->testTeam1->id);

        $team1 = SoftDeletingTeam::find($this->testTeam1->id);
        app(GroupContract::class)->create(['name' => 'testGroup3', 'team_id' => $team1->id]);
        $user->assignGroup('testGroup');

        $team1->delete();

        $team1 = SoftDeletingTeam::onlyTrashed()->find($this->testTeam1->id);

        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.team_foreign_key') => $team1->id]);
        $this->assertDatabaseHas('groups', [config('acl.column_names.team_foreign_key') => $team1->id]);
    }
}