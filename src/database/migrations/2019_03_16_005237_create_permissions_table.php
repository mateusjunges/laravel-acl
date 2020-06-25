<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\Helpers\Config;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissionsTable = Config::get('tables.permissions', 'permissions');
        Schema::create($permissionsTable, function (Blueprint $table) {
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
        $tables = Config::get('tables');
        Schema::dropIfExists($tables['permissions']);
    }
}
