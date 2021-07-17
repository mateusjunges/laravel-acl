<?php

namespace Junges\ACL\Tests\Commands;

use Junges\ACL\Tests\TestCase;

class ShowPermissionsCommandTest extends TestCase
{
    public function test_it_can_show_permissions()
    {
        $this->artisan('permission:show')->assertExitCode(0);
    }

    public function test_it_can_show_permissions_specifying_group_as_string()
    {
        $this->artisan('permission:show', [
            '--group' => 'test-user-group',
        ])->assertExitCode(0);
    }

    public function test_it_can_show_permissions_specifying_group_as_int()
    {
        $this->artisan('permission:show', [
            '--group' => 1,
        ])->assertExitCode(0);
    }

    public function test_it_returns_error_if_the_group_does_not_exist()
    {
        $this->artisan('permission:show', [
            '--group' => 'group-does-not-exist',
        ])->expectsOutput('Group does not exist!');
    }

    public function test_command_output_with_group_parameter()
    {
        $this->testUserGroup->assignAllPermissions();

        $expected = $this->testUserGroup->permissions->map(function ($permission) {
            return [
                'permission' => $permission->name,
                'slug' => $permission->slug,
                'description' => $permission->description,
            ];
        });

        $this->artisan('permission:show', [
            '--group' => 'test-user-group',
        ])->assertExitCode(0)->expectsTable(['Permission', 'Slug', 'Description'], $expected);
    }
}
