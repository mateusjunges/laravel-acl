<?php

namespace Junges\ACL\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;
use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;

class CreateGroupCommandTest extends TestCase
{
    public function setUp(): void
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

    public function test_it_fails_if_the_name_is_not_specified()
    {
        $this->expectException(RuntimeException::class);

        $this->artisan('group:create', [
            'slug' => 'command-test-group',
            'description' => 'Test command group',
        ])->assertExitCode(1);
    }

    public function test_it_throws_group_already_exists_exception_if_group_already_exists()
    {
        $this->artisan('group:create', [
            'name' => 'Command test group',
            'slug' => 'command-test-group',
            'description' => 'Test command group',
        ]);

        $this->assertCount(1, Group::where('slug', 'command-test-group')->get());

        $this->expectException(GroupAlreadyExistsException::class);

        $this->artisan('group:create', [
            'name' => 'Command test group',
            'slug' => 'command-test-group',
            'description' => 'Test command group',
        ]);
    }
}
