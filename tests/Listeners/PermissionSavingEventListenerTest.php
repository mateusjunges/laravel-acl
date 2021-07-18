<?php

namespace Junges\ACL\Tests\Listeners;

use Junges\ACL\Exceptions\PermissionAlreadyExistsException;
use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class PermissionSavingEventListenerTest extends TestCase
{
    public function test_it_throws_exception_if_group_already_exists()
    {
        $this->expectException(PermissionAlreadyExistsException::class);

        Permission::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'This permission give you all access to the system',
        ]);
    }
}
