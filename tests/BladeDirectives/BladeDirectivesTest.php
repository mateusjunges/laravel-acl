<?php

namespace Junges\ACL\Tests\BladeDirectives;

use Illuminate\Support\Facades\Route;

class BladeDirectivesTest extends BladeDirectivesTestCase
{
    public function test_group_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@group($group)<h1>test</h1>@endgroup',
            ['group' => 'groupname'],
            'Expected to return "false"'
        );
    }

    public function test_group_directive_returning_true()
    {
        $this->testUser->assignGroup($this->testUserGroup);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test</h1>',
            '@group($group)<h1>test</h1>@endgroup',
            ['group' => $this->testUserGroup->slug],
            'Expected to return "true"'
        );
    }

    public function test_permission_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@permission($permission)<h1>test</h1>@endpermission',
            ['permission' => $this->testUserPermission],
            'Expected to return "false"'
        );
    }

    public function test_permission_directive_returning_true()
    {
        $this->testUser->assignPermissions($this->testUserPermission);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test</h1>',
            '@permission($permission)<h1>test</h1>@endpermission',
            ['permission' => $this->testUserPermission],
            'Expected to return "true"'
        );
    }

    public function test_else_group_directive_returning_false()
    {
        $this->testUser->assignGroup($this->testUserGroup2);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test else</h1>',
            '@group($group)<h1>test</h1>@elsegroup($elseGroup)<h1>test else</h1>@endgroup',
            [
                'group' => 'groupname',
                'elseGroup' => $this->testUserGroup2
            ],
            'Expected to return "false"'
        );
    }

    public function test_else_permission_directive_returning_true()
    {

    }
}