<?php

namespace Junges\database\seeds;

use Illuminate\Database\Seeder;
use Junges\ACL\database\seeds\GroupHasPermissionSeeder;
use Junges\ACL\database\seeds\GroupSeeder;
use Junges\ACL\database\seeds\PermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(GroupHasPermissionSeeder::class);
    }
}