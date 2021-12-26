<?php

namespace Junges\ACL\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Junges\ACL\Middlewares\PermissionOrGroupMiddleware;

class PermissionOrGroupMiddlewareTest extends MiddlewareTestCase
{
    protected PermissionOrGroupMiddleware $permissionOrGroupMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        $this->permissionOrGroupMiddleware = new PermissionOrGroupMiddleware();
    }
    
    public function testAGuestCannotAccessARouteProtectedByThePermissionOrGroupMiddleware()
    {
        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'testGroup')
        );
    }

    public function testAUserCanAccessARouteProtectedByPermissionOrGroupIfHasThePermissionOrGroup()
    {
        Auth::login($this->testUser);

        $this->testUser->assignGroup('testGroup');
        $this->testUser->assignPermission('edit-articles');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'testGroup|edit-news|edit-articles')
        );

        $this->testUser->revokeGroup('testGroup');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'testGroup|edit-articles')
        );

        $this->testUser->revokePermission('edit-articles');
        $this->testUser->assignGroup('testGroup');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'testGroup|edit-articles')
        );

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionOrGroupMiddleware, ['testGroup', 'edit-articles'])
        );
    }
    
    public function testAUserCanNotAccessARouteProtectedByPermissionOrGroupMiddlewareIfDoesntHavePermissionAndGroup()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'testGroup|edit-articles')
        );

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'missingRole|missingPermission')
        );
    }
    
    public function testUseNotExistingCustomGuardInGroupOrPermission()
    {
        $class = null;

        try {
            $this->permissionOrGroupMiddleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, 'testGroup', 'xxx');
        } catch (InvalidArgumentException $e) {
            $class = get_class($e);
        }

        $this->assertEquals(InvalidArgumentException::class, $class);
    }
    
    public function testUserCanNotAccessPermissionOrGroupWithGuardAdminWhileLoggedInUsingDefaultGuard()
    {
        Auth::login($this->testUser);

        $this->testUser->assignGroup('testGroup');
        $this->testUser->assignPermission('edit-articles');

        $this->assertEquals(
            403,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'edit-articles|testGroup', 'admin')
        );
    }

    public function testUserCanAccessPermissionOrGroupWithGuardAdminWhileLoggedInUsingAdminGuard()
    {
        Auth::guard('admin')->login($this->testAdmin);

        $this->testAdmin->assignGroup('testAdminGroup');
        $this->testAdmin->assignPermission('admin-permission');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->permissionOrGroupMiddleware, 'admin-permission|testAdminGroup', 'admin')
        );
    }
}
