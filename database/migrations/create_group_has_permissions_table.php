<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\AclRegistrar;

class CreateGroupHasPermissionsTable extends Migration
{
    public function up()
    {
        $groupHasPermissionTable = config('acl.tables.group_has_permissions', 'group_has_permissions');
        $groupsTable = config('acl.tables.groups', 'groups');
        $permissionsTable = config('acl.tables.permissions', 'permissions');

        Schema::create($groupHasPermissionTable, function (Blueprint $table) use ($groupsTable, $permissionsTable) {
            $table->unsignedBigInteger(AclRegistrar::$pivotPermission);
            $table->unsignedBigInteger(AclRegistrar::$pivotGroup);

            $table->foreign(AclRegistrar::$pivotPermission)
                ->references('id')
                ->on($permissionsTable)
                ->cascadeOnDelete();

            $table->foreign(AclRegistrar::$pivotGroup)
                ->references('id')
                ->on($groupsTable)
                ->cascadeOnDelete();

            $table->primary([AclRegistrar::$pivotPermission, AclRegistrar::$pivotGroup], 'group_has_permission_permission_id_group_id_primary');
        });
    }

    public function down()
    {
        $groupHasPermissionsTable = config('acl.tables.group_has_permissions', 'group_has_permissions');

        Schema::dropIfExists($groupHasPermissionsTable);
    }
}
