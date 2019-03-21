<?php

namespace MateusJunges\ACL\database\seeds;

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionModel = app(config('acl.models.permission'));
        $permissionModel->create([
           'name'        => '',
           'slug'        => '',
           'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
        $permissionModel->create([
            'name'        => '',
            'slug'        => '',
            'description' => ''
        ]);
    }
}
