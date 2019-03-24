<?php

namespace Junges\ACL\database\seeds;

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $groupModel = app(config('acl.models.group'));
       $groupModel->create([
          'name'        => 'Administração',
          'slug'        => 'administracao',
          'description' => 'Administração do sistem. Tem permissão de acesso total.'
       ]);
    }
}
