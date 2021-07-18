<?php

namespace Junges\ACL\Tests\BladeDirectives;

class BladeDirectivesTest extends BladeDirectivesTestCase
{
    public function test_group_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@group($group)<h1>test</h1>@endgroup',
            ['group' => 'some-group'],
            'Expected to return ""'
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
            'Expected to return "<h1>test</h1>"'
        );
    }

    public function test_permission_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@permission($permission)<h1>test</h1>@endpermission',
            ['permission' => $this->testUserPermission],
            'Expected to return ""'
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
            'Expected to return "<h1>test</h1>"'
        );
    }

    public function test_else_group_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@group($group)<h1>test</h1>@elsegroup($elseGroup)<h1>test else</h1>@endgroup',
            [
                'group' => 'some-group',
                'elseGroup' => 'another-group',
            ],
            'Expected to return ""'
        );
    }

    public function test_else_group_directive_returning_true()
    {
        $this->testUser->assignGroup($this->testUserGroup2);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test else</h1>',
            '@group($group)<h1>test</h1>@elsegroup($elseGroup)<h1>test else</h1>@endgroup',
            [
                'group' => 'groupname',
                'elseGroup' => $this->testUserGroup2,
            ],
            'Expected to return "<h1>test else</h1>"'
        );
    }

    public function test_else_permission_directive_returning_true()
    {
        $this->testUser->assignPermissions($this->testUserPermission);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test else</h1>',
            '@permission($permission)<h1>test</h1>@elsepermission($elsePermission)<h1>test else</h1>@endpermission',
            [
                'permission' => 'some-permission',
                'elsePermission' => $this->testUserPermission,
            ],
            'Expected to return "<h1>test else</h1>"'
        );
    }

    public function test_else_permission_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@permission($permission)<h1>test</h1>@elsepermission($elsePermission)<h1>test else</h1>@endpermission',
            [
                'permission' => 'some-permission',
                'elsePermission' => 'another-permission',
            ],
            'Expected to return ""'
        );
    }

    public function test_all_permissions_directive_returning_true()
    {
        $this->testUser->assignPermissions($this->testUserPermission, $this->testUserPermission2);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test</h1>',
            '@allpermission($permission, $permission2)<h1>test</h1>@endallpermission',
            [
                'permission' => $this->testUserPermission,
                'permission2' => $this->testUserPermission2,
            ],
            'Expected to return "<h1>test</h1>"'
        );
    }

    public function test_all_permissions_directive_returning_false()
    {
        $this->testUser->assignPermissions($this->testUserPermission);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@allpermission($permission, $permission2)<h1>test</h1>@endallpermission',
            [
                'permission' => $this->testUserPermission,
                'permission2' => $this->testUserPermission2,
            ],
            'Expected to return ""'
        );
    }

    public function test_any_permission_directive_returning_true()
    {
        $this->testUser->assignPermissions($this->testUserPermission);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test</h1>',
            '@anypermission($permission, $permission2)<h1>test</h1>@endanypermission',
            [
                'permission' => $this->testUserPermission,
                'permission2' => $this->testUserPermission2,
            ],
            'Expected to return "<h1>test</h1>"'
        );
    }

    public function test_any_permission_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@anypermission($permission, $permission2)<h1>test</h1>@endanypermission',
            [
                'permission' => $this->testUserPermission,
                'permission2' => $this->testUserPermission2,
            ],
            'Expected to return ""'
        );
    }

    public function test_any_group_directive_returning_true()
    {
        $this->testUser->assignGroup($this->testUserGroup);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test</h1>',
            '@anygroup($group, $group2)<h1>test</h1>@endanygroup',
            [
                'group' => $this->testUserGroup,
                'group2' => $this->testUserGroup2,
            ],
            'Expected to return "<h1>test</h1>"'
        );
    }

    public function test_any_group_directive_returning_false()
    {
        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@anygroup($group, $group2)<h1>test</h1>@endanygroup',
            [
                'group' => $this->testUserPermission,
                'group2' => $this->testUserPermission2,
            ],
            'Expected to return ""'
        );
    }

    public function test_all_groups_directive_returning_true()
    {
        $this->testUser->assignGroup($this->testUserGroup, $this->testUserGroup2);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '<h1>test</h1>',
            '@allgroups($group, $group2)<h1>test</h1>@endallgroups',
            [
                'group' => $this->testUserGroup,
                'group2' => $this->testUserGroup2,
            ],
            'Expected to return "<h1>test</h1>"'
        );
    }

    public function test_all_groups_directive_returning_false()
    {
        $this->testUser->assignGroup($this->testUserGroup);

        auth()->login($this->testUser);

        $this->assertDirectiveOutput(
            '',
            '@allgroups($group, $group2)<h1>test</h1>@endallgroups',
            [
                'group' => $this->testUserGroup,
                'group2' => $this->testUserGroup2,
            ],
            'Expected to return ""'
        );
    }
}
