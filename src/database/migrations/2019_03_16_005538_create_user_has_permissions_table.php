<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = config('acl.tables');
        Schema::create($tables['user_has_permissions'], function (Blueprint $table) use ($tables){
            $table->bigInteger('user_id', false, true);
            $table->integer('permission_id', false, true);
            $table->foreign('user_id')
                ->references('id')
                ->on($tables['users'])
                ->onDelete('cascade');
            $table->foreign('permission_id')
                ->references('id')
                ->on($tables['permissions'])
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
        $tables = config('acl.tables');
        Schema::dropIfExists($tables['user_has_permissions']);
    }
}
