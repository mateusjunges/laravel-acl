<?php

namespace MateusJunges\database\seeds;

use Illuminate\Database\Seeder;
use MateusJunges\ACL\database\seeds\GroupHasPermissionSeeder;
use MateusJunges\ACL\database\seeds\GroupSeeder;
use MateusJunges\ACL\database\seeds\PermissionsSeeder;

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