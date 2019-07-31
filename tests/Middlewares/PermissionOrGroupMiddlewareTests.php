<?php

namespace Junges\ACL\Tests\Middlewares;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class PermissionOrGroupMiddlewareTests extends MiddlewareTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_if_a_guest_can_not_access_routes_protected_with_group_or_permission_middleware()
    {
        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionOrGroupMiddleware,
                $this->testUserPermission->slug
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    public function test_if_a_guest_can_not_access_routes_protected_by_permission_or_group_middleware()
    {
        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionOrGroupMiddleware,
                [$this->testUserGroup->slug, $this->testUserPermission2->slug]
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    public function test_if_a_logged_in_user_can_access_routes_protected_by_permission_or_group_middleware_if_has_the_permission_or_group()
    {
        Auth::login($this->testUser);

        Auth::user()->assignGroup($this->testUserGroup);

        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionOrGroupMiddleware,
                [$this->testUserGroup->slug, $this->testUserGroup2]
            ),
            ResponseCode::HTTP_OK
        );
    }

    public function test_if_the_logged_in_user_can_not_access_routes_protected_by_permission_or_group_middleware_if_has_not_permission_or_groups()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionOrGroupMiddleware,
                [$this->testUserGroup->slug, $this->testUserPermission2->slug]
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }
}
