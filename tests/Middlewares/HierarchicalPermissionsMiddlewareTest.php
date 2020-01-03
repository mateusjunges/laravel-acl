<?php

namespace Junges\ACL\Tests\Middlewares;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HierarchicalPermissionsMiddlewareTest extends MiddlewareTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_if_a_guest_can_not_access_a_protected_route()
    {
        $this->assertEquals(
            $this->execMiddleware($this->hierarchicalMiddleware, 'edit-news.edit-website'),
            Response::HTTP_FORBIDDEN
        );
    }

    public function test_if_the_logged_in_user_can_access_a_route_protected_by_hierarchical_middleware_if_one_of_the_hierarchical_permissions_match()
    {
        Auth::login($this->testUser);

        Auth::user()->assignPermissions('admin.auth');
        $this->assertEquals(
            $this->execMiddleware($this->hierarchicalMiddleware, 'admin.auth.users'),
            Response::HTTP_OK
        );
    }

    public function test_if_the_logged_in_user_can_not_access_a_route_protected_by_hierarchical_middleware_if_no_hierarchical_permissions_match()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->execMiddleware($this->hierarchicalMiddleware, 'admin.auth.users'),
            Response::HTTP_FORBIDDEN
        );
    }
}
