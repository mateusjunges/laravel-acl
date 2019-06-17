<?php

namespace Junges\ACL\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Junges\ACL\Exceptions\UnauthorizedException;
use Junges\ACL\Middlewares\HierarchicalMiddleware;

class HierarchicalPermissionsTest extends TestCase
{
    protected $hierarchicalMiddleware;

    /**
     * Set up the test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->hierarchicalMiddleware = new HierarchicalMiddleware($this->app);
    }

    /**
     * @test
     */
    public function a_guest_can_not_access_a_protected_route()
    {
        $this->assertEquals(
            $this->execMiddleware($this->hierarchicalMiddleware, 'edit-news.edit-website'),
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_access_a_route_protected_by_hierarchical_middleware_if_one_of_the_hierarchical_permissions_match()
    {
        Auth::login($this->testUser);

        Auth::user()->assignPermissions([
           'admin.auth',
        ]);
        $this->assertEquals(
            $this->execMiddleware($this->hierarchicalMiddleware, 'admin.auth.users'),
            Response::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_not_access_a_route_protected_by_hierarchical_middleware_if_no_hierarchical_permissions_match()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->execMiddleware($this->hierarchicalMiddleware, 'admin.auth.users'),
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * Execute the specified middleware.
     * @param $middleware
     * @param $parameter
     * @return int
     */
    private function execMiddleware($middleware, $parameter)
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
