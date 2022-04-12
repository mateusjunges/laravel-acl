<?php

namespace Junges\ACL\Tests\Commands;

use Junges\ACL\Contracts\Group;
use Junges\ACL\Tests\TestCase;

class CreateGroupTest extends TestCase
{
    public function testItCanCreateAGroup()
    {
        $this->artisan('group:create', [
            'name' => 'new-group',
        ]);

        $this->assertCount(1, app(Group::class)->where('name', 'new-group')->get());
        $this->assertCount(0, app(Group::class)->where('name', 'new-group')->first()->permissions);
    }

    public function testItCanCreateAGroupWithSpecificGuard()
    {
        $this->artisan('group:create', [
            'name' => 'new-group',
            'guard' => 'api',
        ]);

        $this->assertCount(1, app(Group::class)->where('name', 'new-group')
            ->where('guard_name', 'api')
            ->get());
    }

    public function testItCanCreateAGroupWithoutDuplication()
    {
        $this->artisan('group:create', ['name' => 'new-group']);
        $this->artisan('group:create', ['name' => 'new-group']);

        $this->assertCount(1, app(Group::class)->where('name', 'new-group')->get());
        $this->assertCount(0, app(Group::class)->where('name', 'new-group')->first()->permissions);
    }

    public function testItCanCreateAGroupAndPermissionAtSameTime()
    {
        $this->artisan('group:create', [
            'name' => 'new-group',
            'permissions' => 'first permission | second permission',
        ]);

        $group = app(Group::class)->where('name', 'new-group')->first();

        $this->assertTrue($group->hasPermission('first permission'));
        $this->assertTrue($group->hasPermission('second permission'));
    }
}
