<?php


namespace Junges\ACL\Test;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Junges\ACL\Exceptions\UnauthorizedException;
use Junges\ACL\Middlewares\GroupMiddleware;
use Junges\ACL\Middlewares\PermissionMiddleware;
use Junges\ACL\Middlewares\PermissionOrGroupMiddleware;

class MiddlewareTest extends TestCase
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
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->permissionMiddleware = new PermissionMiddleware($this->app);
        $this->groupMiddleware = new GroupMiddleware($this->app);
        $this->permissionOrGroupMiddleware = new PermissionOrGroupMiddleware($this->app);
    }

    /**
     * @test
     */
    public function guest_can_not_access_routes_protected_with_permission_middleware()
    {
        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionMiddleware, $this->testUserPermission->slug),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function guest_can_not_access_routes_protected_with_group_middleware()
    {
        $this->assertEquals(
            $this->execMiddleware(
                $this->groupMiddleware,
                $this->testUserGroup->slug
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function guest_can_not_access_routes_protected_with_group_or_permission_middleware()
    {
        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionOrGroupMiddleware,
                $this->testUserPermission->slug
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_access_routes_protected_with_permission_group_if_has_the_specified_permission()
    {
        Auth::login($this->testUser);

        Auth::user()->assignPermissions([$this->testUserPermission]);

        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionMiddleware,
                $this->testUserPermission->slug
            ),
            ResponseCode::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_access_routes_protected_with_two_permissions_if_has_at_least_one_of_those_permissions()
    {
        Auth::login($this->testUser);

        Auth::user()->assignPermissions([$this->testUserPermission]);

        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionMiddleware,
                [$this->testUserPermission->slug, $this->testUserPermission2->slug]
            ),
            ResponseCode::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_access_routes_protected_with_group_middleware_if_has_the_specified_group()
    {
        Auth::login($this->testUser);

        Auth::user()->assignGroup([$this->testUserGroup]);

        $this->assertEquals(
            $this->execMiddleware(
                $this->groupMiddleware,
                $this->testUserGroup->slug
            ),
            ResponseCode::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_not_access_routes_protected_by_permissions_middleware_if_have_a_different_permission()
    {
        Auth::login($this->testUser);

        Auth::user()->assignPermissions([$this->testUserPermission]);

        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionMiddleware,
                $this->testUserPermission2->slug
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_not_access_routes_protected_by_group_middleware_if_have_a_different_group()
    {
        Auth::login($this->testUser);

        Auth::user()->assignGroup([$this->testUserGroup]);

        $this->assertEquals(
            $this->execMiddleware(
                $this->groupMiddleware,
                $this->testAdminGroup->slug
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function guest_can_not_access_routes_protected_by_permission_or_group_middleware()
    {
        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionOrGroupMiddleware,
                [$this->testUserGroup->slug, $this->testUserPermission2->slug]
            ),
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_access_routes_protected_by_permission_or_group_middleware_if_has_the_permission_or_group()
    {
        Auth::login($this->testUser);

        Auth::user()->assignGroup([$this->testUserGroup]);

        $this->assertEquals(
            $this->execMiddleware(
                $this->permissionOrGroupMiddleware,
                [$this->testUserGroup->slug, $this->testUserPermission2->slug]
            ),
            ResponseCode::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function logged_in_user_can_not_access_routes_protected_by_permission_or_group_middleware_if_has_not_permission_or_groups()
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

    /**
     * Execute the specified middleware
     * @param $middleware
     * @param $parameter
     * @return int
     */
    private function execMiddleware($middleware, $parameter)
    {
        try{
            return $middleware->handle(new Request(), function (){
               return (new Response())->setContent('<html></html>');
            }, $parameter)->status();
        }catch (UnauthorizedException $exception){
            return $exception->getStatusCode();
        }
    }


}
