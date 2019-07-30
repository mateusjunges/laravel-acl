<?php

namespace Junges\ACL\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class CreateGroupCommandTests extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_if_it_can_create_a_group()
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
