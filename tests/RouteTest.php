<?php

namespace Junges\ACL\Tests;

use Illuminate\Http\Response;

class RouteTest extends TestCase
{
    public function testGroupFunction()
    {
        $router = $this->getRouter();

        $router->get('group-test', $this->getRouteResponse())
            ->name('group.test')
            ->withGroup('superadmin');

        $this->assertEquals(['groups:superadmin'], $this->getLastRouteMiddlewareFromRouter($router));
    }

    public function testPermissionFunction()
    {
        $router = $this->getRouter();

        $router->get('permission-test', $this->getRouteResponse())
            ->name('permission.test')
            ->withPermission(['edit articles', 'save articles']);

        $this->assertEquals(['permission:edit articles|save articles'], $this->getLastRouteMiddlewareFromRouter($router));
    }

    public function testGroupAndPermissionFunctionTogether()
    {
        $router = $this->getRouter();

        $router->get('group-permission-test', $this->getRouteResponse())
            ->name('group-permission.test')
            ->withGroup('superadmin|admin')
            ->withPermission('create user|edit user');

        $this->assertEquals(
            [
                'groups:superadmin|admin',
                'permission:create user|edit user',
            ],
            $this->getLastRouteMiddlewareFromRouter($router)
        );
    }

    protected function getLastRouteMiddlewareFromRouter($router)
    {
        return last($router->getRoutes()->get())->middleware();
    }

    protected function getRouter()
    {
        return app('router');
    }

    protected function getRouteResponse()
    {
        return function () {
            return (new Response())->setContent('<html></html>');
        };
    }
}
