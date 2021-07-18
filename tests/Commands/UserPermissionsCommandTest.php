<?php

namespace Junges\ACL\Tests\Commands;

use Junges\ACL\Tests\TestCase;

class UserPermissionsCommandTest extends TestCase
{
    public function test_it_can_show_user_permissions_via_id()
    {
        $this->artisan('user:permissions', [
            'user' => 1,
        ])->assertExitCode(0);
    }

    public function test_it_can_show_user_permissions_via_email()
    {
        $this->artisan('user:permissions', [
            'user' => 'user@user.com',
        ])->assertExitCode(0);
    }

    public function test_it_returns_error_if_cant_find_the_user()
    {
        $this->artisan('user:permissions', [
            'user' => 'user-not-found@user.com',
        ])->assertExitCode(0)->expectsOutput('User not found');
    }

    public function testc_command_output()
    {
        $this->testUser->assignAllPermissions();

        $permissions = $this->testUser->permissions->map(function ($permission) {
            return [
                'name' => $permission->name,
                'slug' => $permission->slug,
                'description' => $permission->description,
            ];
        });

        $headers = ['Name', 'Slug', 'Description'];

        $this->artisan('user:permissions', [
            'user' => 'user@user.com',
        ])->assertExitCode(0)->expectsTable($headers, $permissions);
    }
}
