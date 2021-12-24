<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\AclRegistrar;

class CreateModelHasPermissionsTable extends Migration
{
    public function up()
    {
        $columnNames = config('acl.column_names');

        $modelHasPermissions = config('acl.tables.model_has_permissions', 'model_has_permissions');
        $permissionsTable = config('acl.tables.permissions', 'permissions');

        Schema::create($modelHasPermissions, function (Blueprint $table) use ($permissionsTable, $columnNames) {
            $table->unsignedBigInteger(AclRegistrar::$pivotPermission);
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_id_model_type_index');

            $table->foreign(AclRegistrar::$pivotPermission)
                ->references('id')
                ->on($permissionsTable)
                ->cascadeOnDelete();

            $table->primary([AclRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                'model_has_permissions_permission_model_type_primary');
        });
    }

    public function down()
    {
        $userHasPermissionTable = config('acl.tables.user_has_permissions', 'user_has_permissions');
        Schema::dropIfExists($userHasPermissionTable);
    }
}
