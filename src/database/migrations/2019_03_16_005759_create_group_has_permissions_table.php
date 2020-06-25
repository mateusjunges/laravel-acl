<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\Helpers\Config;

class CreateGroupHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $groupHasPermissionTable = Config::get('tables.group_has_permissions', 'group_has_permissions');
        $groupsTable = Config::get('tables.groups', 'groups');
        $permissionsTable = Config::get('tables.permissions', 'permissions');

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
        $groupHasPermissionsTable = Config::get('tables.group_has_permissions', 'group_has_permissions');
        Schema::dropIfExists($groupHasPermissionsTable);
    }
}
