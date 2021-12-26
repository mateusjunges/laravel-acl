<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\AclRegistrar;

class CreateModelHasGroupsTable extends Migration
{
    public function up()
    {
        $columnNames = config('acl.column_names');
        $modelHasGroups = config('acl.tables.model_has_groups', 'model_has_groups');
        $groupsTable = config('acl.tables.groups', 'groups');
        $teams = config('acl.teams');

        Schema::create($modelHasGroups, function (Blueprint $table) use ($groupsTable, $columnNames, $teams) {
            $table->unsignedBigInteger(AclRegistrar::$pivotGroup);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_groups_model_id_model_type_index');

            $table->foreign(AclRegistrar::$pivotGroup)
                ->references('id')
                ->on($groupsTable)
                ->cascadeOnDelete();

            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_groups_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], AclRegistrar::$pivotGroup, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_groups_group_model_type_primary');
            } else {
                $table->primary([AclRegistrar::$pivotGroup, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_groups_group_model_type_primary');
            }
        });
    }

    public function down()
    {
        $userHasGroupsTable = config('acl.tables.user_has_groups', 'user_has_groups');
        Schema::dropIfExists($userHasGroupsTable);
    }
}
