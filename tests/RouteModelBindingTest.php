<?php

namespace Junges\ACL\Tests;

class RouteModelBindingTest extends TestCase
{
    public function test_group_model_can_get_the_correct_route_key_name()
    {
        $group = new Group();

        // Test the default key:
        $this->assertEquals('slug', $group->getRouteKeyName());

        $this->app['config']->set('acl.route_model_binding_keys.group_model', 'slug');
        $this->assertEquals('slug', $group->getRouteKeyName());
        $this->app['config']->set('acl.route_model_binding_keys.group_model', 'id');
        $this->assertEquals('id', $group->getRouteKeyName());

        $this->app['config']->set('acl.route_model_binding_keys.group_model', '');
        $this->assertEquals('', $group->getRouteKeyName());
    }

    public function test_permission_model_can_get_the_correct_route_key_name()
    {
        $permission = new Permission();

        // Test the default key:
        $this->assertEquals('slug', $permission->getRouteKeyName());

        $this->app['config']->set('acl.route_model_binding_keys.permission_model', 'slug');
        $this->assertEquals('slug', $permission->getRouteKeyName());
        $this->app['config']->set('acl.route_model_binding_keys.permission_model', 'id');
        $this->assertEquals('id', $permission->getRouteKeyName());

        $this->app['config']->set('acl.route_model_binding_keys.permission_model', '');
        $this->assertEquals('', $permission->getRouteKeyName());
    }
}
