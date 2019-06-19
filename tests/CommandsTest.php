<?php

namespace Junges\Tests;

use Illuminate\Support\Facades\Artisan;
use Junges\ACL\Exceptions\PermissionAlreadyExistsException;
use Junges\ACL\Test\Group;
use Junges\ACL\Test\Permission;
use Junges\ACL\Test\TestCase;

class CommandsTest extends TestCase
{
    /**
     * Command test config.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_can_create_a_permission()
    {

        $permission = Artisan::call('permission:create', [
            'name' => 'Command test permission',
            'slug' => 'command-test-permission',
            'description' => 'Command test',
        ]);

        $this->assertCount(1,Permission::where('slug', 'command-test-permission')->get());
        $this->assertEquals(0, $permission);
    }


    /**
     * @test
     */
    public function it_can_create_a_group()
    {
        $group = Artisan::call('group:create', [
            'name' => 'Command test group',
            'slug' => 'command-test-group',
            'description' => 'Test command group',
        ]);
        $this->assertEquals(0, $group);
        $this->assertCount(1, Group::where('slug', 'command-test-group')->get());
    }
}
