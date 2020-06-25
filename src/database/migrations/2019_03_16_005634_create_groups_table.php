<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\Helpers\Config;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $groupsTable = Config::get('tables.groups', 'groups');
        Schema::create($groupsTable, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->nullable(false);
            $table->string('slug')->unique()->nullable(false);
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $groupsTable = Config::get('tables.groups', 'groups');
        Schema::dropIfExists($groupsTable);
    }
}
