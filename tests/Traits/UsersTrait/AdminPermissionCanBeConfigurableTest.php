<?php

namespace Junges\ACL\Tests\Traits\UsersTrait;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Helpers\Config;

class AdminPermissionCanBeConfigurableTest extends TestCase
{
    /** @test */
    public function ensure_admin_permission_can_be_configurable()
    {
        $this->assertEquals('admin', Config::get('admin_permission'));

        $this->app['config']->set('acl.admin_permission', 'super-admin');

        $this->assertEquals('super-admin', Config::get('admin_permission'));
    }
}
