<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = config('acl.tables');
        Schema::create($tables['user_has_groups'], function (Blueprint $table)  use ($tables) {
            $table->bigInteger('user_id', false, true);
            $table->integer('group_id', false, true);
            $table->foreign('user_id')
                ->references('id')
                ->on($tables['users'])
                ->onDelete('cascade');
            $table->foreign('group_id')
                ->references('id')
                ->on($tables['groups'])
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
        $tables = config('acl.tables');
        Schema::dropIfExists($tables['user_has_groups']);
    }
}
