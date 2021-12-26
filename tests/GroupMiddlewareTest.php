<?php

namespace Junges\ACL\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Junges\ACL\Exceptions\UnauthorizedException;
use Junges\ACL\Middlewares\GroupMiddleware;

class GroupMiddlewareTest extends MiddlewareTestCase
{
    protected GroupMiddleware $groupMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        $this->groupMiddleware = new GroupMiddleware();
    }

    public function testAGuestCannotAccessARouteProtectedByGroupMiddleware()
    {
        $this->assertEquals(
            403,
            $this->runMiddleware($this->groupMiddleware, 'testGroup')
        );
    }

    public function testAUserCannotAccessARouteProtectedByGroupMiddlewareOfAnotherGuard()
    {
        Auth::login($this->testUser);

        $this->testUser->assignGroup('testGroup');

        $this->assertEquals(
            403,
            $this->runMiddleware($this->groupMiddleware, 'testAdminGroup')
        );
    }

    public function testAUserCanAccessARouteProtectedByGroupMiddelwareIfHaveThisGroup()
    {
        Auth::login($this->testUser);

        $this->testUser->assignGroup('testGroup');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->groupMiddleware, 'testGroup')
        );
    }

    public function testAUserCanAccessARouteProtectedByThisGroupMiddlewareIfHaveOneOfTheGroups()
    {
        Auth::login($this->testUser);

        $this->testUser->assignGroup('testGroup');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->groupMiddleware, 'testGroup|testGroup2')
        );

        $this->assertEquals(
            200,
            $this->runMiddleware($this->groupMiddleware, ['testGroup2', 'testGroup'])
        );
    }

    public function testAUserCannotAccessARouteProtectedByTheGroupMiddlewareIfHaveADifferentGroup()
    {
        Auth::login($this->testUser);

        $this->testUser->assignGroup(['testGroup']);

        $this->assertEquals(
            403,
            $this->runMiddleware($this->groupMiddleware, 'testGroup2')
        );
    }

    public function testAUserCannotAccessARouteProtectedByGroupMiddlewareIfHaveNoGroups()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            403,
            $this->runMiddleware($this->groupMiddleware, 'testGroup|testGroup2')
        );
    }

    public function testAUserCannotAccessARouteProtectedByGroupMiddlewareIfGroupIsUndefined()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            403,
            $this->runMiddleware($this->groupMiddleware, '')
        );
    }

    public function testTheRequiredGroupsCanBeFetchedFromTheException()
    {
        Auth::login($this->testUser);

        $requiredGroups = [];

        try {
            $this->groupMiddleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, 'some-group');
        } catch (UnauthorizedException $e) {
            $requiredGroups = $e->getRequiredGroups();
        }

        $this->assertEquals(['some-group'], $requiredGroups);
    }

    public function testUseNotExistingCustomGuardInGroup()
    {
        $class = null;

        try {
            $this->groupMiddleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, 'testGroup', 'xxx');
        } catch (InvalidArgumentException $e) {
            $class = get_class($e);
        }

        $this->assertEquals(InvalidArgumentException::class, $class);
    }

    public function user_can_not_access_group_with_guard_admin_while_login_using_default_guard()
    {
        Auth::login($this->testUser);

        $this->testUser->assignGroup('testGroup');

        $this->assertEquals(
            403,
            $this->runMiddleware($this->groupMiddleware, 'testGroup', 'admin')
        );
    }

    public function testUserCanAccessGroupsWithGuardAminWhileLoginUsingAdminGuard()
    {
        Auth::guard('admin')->login($this->testAdmin);

        $this->testAdmin->assignGroup('testAdminGroup');

        $this->assertEquals(
            200,
            $this->runMiddleware($this->groupMiddleware, 'testAdminGroup', 'admin')
        );
    }
}