<?php

namespace Junges\ACL\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Exceptions\UnauthorizedException;
use Junges\ACL\Middlewares\PermissionMiddleware;

class PermissionMiddlewareTest extends MiddlewareTestCase
{
    protected PermissionMiddleware $permissionMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        $this->permissionMiddleware = new PermissionMiddleware();
    }

    public function testAGuestCannotAccessARouteProtectedByPermissionMiddleware()
    {
        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles')
        );
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_the_permission_middleware_of_a_different_guard()
    {
        // These permissions are created fresh here in reverse order of guard being applied, so they are not "found first" in the db lookup when matching
        app(PermissionContract::class)->create(['name' => 'admin-permission2', 'guard_name' => 'web']);
        $p1 = app(PermissionContract::class)->create(['name' => 'admin-permission2', 'guard_name' => 'admin']);
        app(PermissionContract::class)->create(['name' => 'edit-articles2', 'guard_name' => 'admin']);
        $p2 = app(PermissionContract::class)->create(['name' => 'edit-articles2', 'guard_name' => 'web']);

        Auth::guard('admin')->login($this->testAdmin);

        $this->testAdmin->assignPermission($p1);

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionMiddleware, 'admin-permission2', 'admin')
        );

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles2', 'admin')
        );

        Auth::login($this->testUser);

        $this->testUser->assignPermission($p2);

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles2', 'web')
        );

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionMiddleware, 'admin-permission2', 'web')
        );
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_permission_middleware_if_have_this_permission()
    {
        Auth::login($this->testUser);

        $this->testUser->assignPermission('edit-articles');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles')
        );
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_this_permission_middleware_if_have_one_of_the_permissions()
    {
        Auth::login($this->testUser);

        $this->testUser->assignPermission('edit-articles');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionMiddleware, 'edit-news|edit-articles')
        );

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionMiddleware, ['edit-news', 'edit-articles'])
        );
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_the_permission_middleware_if_have_a_different_permission()
    {
        Auth::login($this->testUser);

        $this->testUser->assignPermission('edit-articles');

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionMiddleware, 'edit-news')
        );
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_permission_middleware_if_have_not_permissions()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles|edit-news')
        );
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_permission_middleware_if_has_permission_via_role()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles')
        );

        $this->testUserGroup->assignPermission('edit-articles');
        $this->testUser->assignGroup('testGroup');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles')
        );
    }

    /** @test */
    public function the_required_permissions_can_be_fetched_from_the_exception()
    {
        Auth::login($this->testUser);

        $requiredPermissions = [];

        try {
            $this->permissionMiddleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, 'some-permission');
        } catch (UnauthorizedException $e) {
            $requiredPermissions = $e->getRequiredPermissions();
        }

        $this->assertEquals(['some-permission'], $requiredPermissions);
    }

    public function testUseNotExistingCustomGuardInPermission()
    {
        $class = null;

        try {
            $this->permissionMiddleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, 'edit-articles', 'xxx');
        } catch (InvalidArgumentException $e) {
            $class = get_class($e);
        }

        $this->assertEquals(InvalidArgumentException::class, $class);
    }

    public function testUserCanNotAccessPermissionWithGuardAdminWhileLoginUsingDefaultGuard()
    {
        Auth::login($this->testUser);

        $this->testUser->assignPermission('edit-articles');

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionMiddleware, 'edit-articles', 'admin')
        );
    }

    public function testUserCanAccessPermissionWithGuardAdminWhileLoginUsingAdminGuard()
    {
        Auth::guard('admin')->login($this->testAdmin);

        $this->testAdmin->assignPermission('admin-permission');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionMiddleware, 'admin-permission', 'admin')
        );
    }
}
