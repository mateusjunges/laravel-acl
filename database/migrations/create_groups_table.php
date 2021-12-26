<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        $groupsTable = config('acl.tables.groups', 'groups');
        $teams = config('acl.teams');
        $columnNames = config('acl.column_names');

        Schema::create($groupsTable, function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id');

            if ($teams || config("acl.testing")) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'groups_team_foreign_key_index');
            }

            $table->string('name');
            $table->string('guard_name');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            if ($teams || config('acl.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });
    }

    public function down()
    {
        $groupsTable = config('acl.tables.groups', 'groups');
        Schema::dropIfExists($groupsTable);
    }
}
