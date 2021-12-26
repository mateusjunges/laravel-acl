<?php

namespace Junges\ACL\Tests;

use Illuminate\Contracts\Auth\Access\Gate;

class CustomGateTest extends TestCase
{
    protected function getEnvironmentSetup($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('acl.register_permission_check_method', false);
    }

    public function testItDoesNotRegisterMethodToCheckPermissionOnGate()
    {
        $this->testUser->assignPermission('edit-articles');

        $this->assertEmpty(app(Gate::class)->abilities());
        $this->assertFalse($this->testUser->can('edit-articles'));
    }

    public function testItCanAuthorizeUsingCustomPermissionCheckMethod()
    {
        app(Gate::class)->define('edit-articles', fn () => true);

        $this->assertArrayHasKey('edit-articles', app(Gate::class)->abilities());
        $this->assertTrue($this->testUser->can('edit-articles'));
    }
}