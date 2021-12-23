<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        $permissionsTable = config('acl.tables.permissions', 'permissions');
        Schema::create($permissionsTable, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });
    }

    public function down()
    {
        $tables = config('acl.tables');
        Schema::dropIfExists($tables['permissions']);
    }
}
