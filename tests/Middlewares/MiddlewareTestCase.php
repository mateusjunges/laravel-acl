<?php

namespace Junges\ACL\Tests\Middlewares;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Junges\ACL\Exceptions\UnauthorizedException;
use Junges\ACL\Middlewares\GroupMiddleware;
use Junges\ACL\Middlewares\HierarchicalPermissionsMiddleware;
use Junges\ACL\Middlewares\PermissionMiddleware;
use Junges\ACL\Middlewares\PermissionOrGroupMiddleware;
use Junges\ACL\Tests\TestCase;

class MiddlewareTestCase extends TestCase
{
    /**
     * @var PermissionMiddleware
     */
    protected $permissionMiddleware;

    /**
     * @var GroupMiddleware
     */
    protected $groupMiddleware;

    /**
     * @var PermissionOrGroupMiddleware
     */
    protected $permissionOrGroupMiddleware;

    /**
     * @var HierarchicalPermissionsMiddleware
     */
    protected $hierarchicalMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->permissionMiddleware = new PermissionMiddleware($this->app);
        $this->groupMiddleware = new GroupMiddleware($this->app);
        $this->permissionOrGroupMiddleware = new PermissionOrGroupMiddleware($this->app);
        $this->hierarchicalMiddleware = new HierarchicalPermissionsMiddleware($this->app);
    }

    /**
     * Execute the specified middleware.
     * @param $middleware
     * @param $parameter
     * @return int
     */
    protected function execMiddleware($middleware, $parameter)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, $parameter)->status();
        } catch (UnauthorizedException $exception) {
            return $exception->getStatusCode();
        }
    }
}
