<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userHasPermissionTable = config('acl.tables.user_has_permissions',
            'user_has_permissions');
        $permissionsTable = config('acl.tables.permissions', 'permissions');
        $usersTable = config('acl.tables.users', 'users');
        Schema::create($userHasPermissionTable,
            function (Blueprint $table) use ($permissionsTable, $usersTable) {
                $table->bigInteger('user_id', false, true);
                $table->bigInteger('permission_id', false, true);
                $table->foreign('user_id')
                    ->references('id')
                    ->on($usersTable)
                    ->onDelete('cascade');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on($permissionsTable)
                    ->onDelete('cascade');
                $table->primary(['user_id', 'permission_id']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $userHasPermissionTable = config('acl.tables.user_has_permissions', 'user_has_permissions');
        Schema::dropIfExists($userHasPermissionTable);
    }
}
