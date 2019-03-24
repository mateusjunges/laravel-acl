<?php

namespace Junges\ACL\database\seeds;

use Illuminate\Database\Seeder;

class GroupHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupHasPermissionModel = app(config('acl.models.UserHasPermission'));
    }
}
