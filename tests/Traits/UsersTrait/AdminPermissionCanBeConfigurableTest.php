<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;

class AdminPermissionCanBeConfigurableTest extends TestCase
{
    /** @test */
    public function ensure_admin_permission_can_be_configurable()
    {
        $this->assertEquals('admin', config('acl.admin_permission'));

        $this->app['config']->set('acl.admin_permission', 'super-admin');

        $this->assertEquals('super-admin', config('acl.admin_permission'));
    }
}
