<?php

namespace Junges\ACL\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Junges\ACL\Exceptions\PermissionAlreadyExistsException;
use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class CreatePermissionCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_it_can_create_a_permission()
    {
        $permission = Artisan::call('permission:create', [
            'name' => 'Command test permission',
            'slug' => 'command-test-permission',
            'description' => 'Command test',
        ]);

        $this->assertCount(1, Permission::where('slug', 'command-test-permission')->get());
        $this->assertEquals(0, $permission);
    }

    public function test_it_throws_exception_if_a_permission_already_exists()
    {
        $this->artisan('permission:create', [
            'name' => 'Command test permission',
            'slug' => 'command-test-permission',
            'description' => 'Command test',
        ])->assertExitCode(0);

        $this->assertCount(1, Permission::where('slug', 'command-test-permission')->get());

        $this->expectException(PermissionAlreadyExistsException::class);

        $this->artisan('permission:create', [
            'name' => 'Command test permission',
            'slug' => 'command-test-permission',
            'description' => 'Command test',
        ]);
    }
}
