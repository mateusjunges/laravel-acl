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
           'name'        => 'Visualizar permissões de usuário',
           'slug'        => 'view-user-permissions',
           'description' => 'Permite visualizar as permissões atribuídas a um usuário'
        ]);
        $permissionModel->create([
            'name'        => 'Remover permissão de usuário',
            'slug'        => 'remove-user-permission',
            'description' => 'Permite remover uma permissão de um usuário'
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
