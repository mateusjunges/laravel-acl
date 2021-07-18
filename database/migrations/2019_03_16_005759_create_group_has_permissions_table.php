<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $groupHasPermissionTable = config('acl.tables.group_has_permissions', 'group_has_permissions');
        $groupsTable = config('acl.tables.groups', 'groups');
        $permissionsTable = config('acl.tables.permissions', 'permissions');

        Schema::create($groupHasPermissionTable,
            function (Blueprint $table) use ($groupsTable, $permissionsTable) {
                $table->bigInteger('group_id', false, true);
                $table->bigInteger('permission_id', false, true);
                $table->foreign('group_id')
                    ->references('id')
                    ->on($groupsTable)
                    ->onDelete('cascade');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on($permissionsTable)
                    ->onDelete('cascade');
                $table->primary(['group_id', 'permission_id']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $groupHasPermissionsTable = config('acl.tables.group_has_permissions', 'group_has_permissions');
        Schema::dropIfExists($groupHasPermissionsTable);
    }
}
