<?php

namespace Junges\Tests;

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
        $this->artisan('permission:create', [
            'name' => 'Command test permission',
            'slug' => 'command-test-permission',
            'description' => 'Command test',
        ])->assertExitCode(0);
    }

    /**
     * @test
     */
    public function it_can_create_a_group()
    {
        $this->artisan('group:create', [
            'name' => 'Command test group',
            'slug' => 'command-test-group',
            'description' => 'Test command group',
        ])->assertExitCode(0);
    }
}
