<?php

namespace Junges\ACL\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Junges\ACL\Contracts\Permission as PermissionContract;

class MultipleGuardsTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('auth.guards', [
            'web' => ['driver' => 'session', 'provider' => 'users'],
            'api' => ['driver' => 'token', 'provider' => 'users'],
            'jwt' => ['driver' => 'token', 'provider' => 'users'],
            'abc' => ['driver' => 'abc'],
        ]);

        $this->setUpRoutes();
    }
    
    public function setUpRoutes(): void
    {
        Route::middleware('auth:api')->get('/check-api-guard-permission', function (Request $request) {
            return [
                'status' => $request->user()->checkPermission('use_api_guard'),
            ];
        });
    }

    public function testItCanGiveAPermissionToAModelThatIsUsedByMultipleGuards()
    {
        $this->testUser->assignPermission(app(PermissionContract::class)::create([
            'name' => 'do_this',
            'guard_name' => 'web',
        ]));

        $this->testUser->assignPermission(app(PermissionContract::class)::create([
            'name' => 'do_that',
            'guard_name' => 'api',
        ]));

        $this->assertTrue($this->testUser->checkPermission('do_this', 'web'));
        $this->assertTrue($this->testUser->checkPermission('do_that', 'api'));
        $this->assertFalse($this->testUser->checkPermission('do_that', 'web'));
    }

    public function testItCanHonourGuardNameFunctionOnModelForOverridingGuardNameProperty()
    {
        $user = Manager::create(['email' => 'manager@test.com']);

        $user->assignPermission(app(PermissionContract::class)::create([
            'name' => 'do_jwt',
            'guard_name' => 'jwt',
        ]));

        $this->assertTrue($user->checkPermission('do_jwt', 'jwt'));
        $this->assertTrue($user->hasPermission('do_jwt', 'jwt'));

        $this->assertFalse($user->checkPermission('do_jwt', 'web'));
    }
}
