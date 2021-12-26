<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Junges\ACL\AclRegistrar;

class AddTeamsFields extends Migration
{
    public function up()
    {
        $teams = config('acl.teams');
        $tableNames = config('acl.tables');
        $columnNames = config('acl.column_names');

        if (! $teams) {
            return;
        }
        
        if (empty($tableNames)) {
            throw new Exception('Error: config/acl.php not loaded. Run [php artisan config:clear] and try again.');
        }
        
        if (empty($columnNames['team_foreign_key'] ?? null)) {
            throw new Exception('Error: team_foreign_key on config/acl.php not loaded. Run [php artisan config:clear] and try again.');
        }

        if (! Schema::hasColumn($tableNames['groups'], $columnNames['team_foreign_key'])) {
            Schema::table($tableNames['groups'], function (Blueprint $table) use ($columnNames) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'groups_team_foreign_key_index');
            });
        }

        if (! Schema::hasColumn($tableNames['model_has_permissions'], $columnNames['team_foreign_key'])) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->default('1');;
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign([AclRegistrar::$pivotPermission]);
                }
                $table->dropPrimary();

                $table->primary([$columnNames['team_foreign_key'], AclRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
                if (DB::getDriverName() !== 'sqlite') {
                    $table->foreign(AclRegistrar::$pivotPermission)
                        ->references('id')->on($tableNames['permissions'])->onDelete('cascade');
                }
            });
        }

        if (! Schema::hasColumn($tableNames['model_has_groups'], $columnNames['team_foreign_key'])) {
            Schema::table($tableNames['model_has_groups'], function (Blueprint $table) use ($tableNames, $columnNames) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->default('1');;
                $table->index($columnNames['team_foreign_key'], 'model_has_groups_team_foreign_key_index');

                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign([AclRegistrar::$pivotGroup]);
                }
                $table->dropPrimary();

                $table->primary([$columnNames['team_foreign_key'], AclRegistrar::$pivotGroup, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_groups_group_model_type_primary');
                if (DB::getDriverName() !== 'sqlite') {
                    $table->foreign(AclRegistrar::$pivotGroup)
                        ->references('id')->on($tableNames['groups'])->onDelete('cascade');
                }
            });
        }

        app('cache')
            ->store(config('acl.cache.store') != 'default' ? config('acl.cache.store') : null)
            ->forget(config('acl.cache.key'));
    }
}
