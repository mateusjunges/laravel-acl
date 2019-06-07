<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userHasGroupsTable = config('acl.tables.user_has_groups', 'user_has_groups');
        $usersTable = config('acl.tables.users', 'users');
        $groupsTable = config('acl.tables.groups', 'groups');
        Schema::create($userHasGroupsTable,
            function (Blueprint $table) use ($usersTable, $groupsTable) {
                $table->bigInteger('user_id', false, true);
                $table->bigInteger('group_id', false, true);
                $table->foreign('user_id')
                    ->references('id')
                    ->on($usersTable)
                    ->onDelete('cascade');
                $table->foreign('group_id')
                    ->references('id')
                    ->on($groupsTable)
                    ->onDelete('cascade');
                $table->primary(['user_id', 'group_id']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $userHasGroupsTable = config('acl.tables.user_has_groups', 'user_has_groups');
        Schema::dropIfExists($userHasGroupsTable);
    }
}
