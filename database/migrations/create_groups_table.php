<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        $groupsTable = config('acl.tables.groups', 'groups');
        Schema::create($groupsTable, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('guard_name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });
    }

    public function down()
    {
        $groupsTable = config('acl.tables.groups', 'groups');
        Schema::dropIfExists($groupsTable);
    }
}
