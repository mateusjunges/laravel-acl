<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = config('acl.tables');
        Schema::create($tables['group_has_permissions'], function (Blueprint $table) use ($tables) {
            $table->integer('group_id', false, true);
            $table->integer('permission_id', false, true);
            $table->foreign('group_id')
                ->references('id')
                ->on($tables['groups'])
                ->onDelete('cascade');
            $table->foreign('permission_id')
                ->references('id')
                ->on($tables['permissions'])
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
        $tables = config('acl.tables');
        Schema::dropIfExists($tables['group_has_permissions']);
    }
}
