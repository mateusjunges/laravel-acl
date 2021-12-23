<?php

namespace Junges\ACL\Tests;

use CreateGroupHasPermissionsTable;
use CreateGroupsTable;
use CreateModelHasGroupsTable;
use CreateModelHasPermissionsTable;
use CreatePermissionsTable;
use Facade\Ignition\IgnitionServiceProvider;
use Illuminate\Cache\DatabaseStore;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\AclRegistrar;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Providers\ACLAuthServiceProvider;
use Junges\ACL\Providers\ACLEventsServiceProvider;
use Junges\ACL\Providers\ACLServiceProvider;
use Junges\ACL\Providers\ACLViewServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @var User
     */
    protected $testUser;

    /**
     * @var Admin
     */
    protected $testAdmin;

    /**
     * @var Group
     */
    protected $testUserGroup;

    /**
     * @var Group
     */
    protected $testAdminGroup;

    /**
     * @var Permission
     */
    protected $testUserPermission;

    /**
     * @var Permission
     */
    protected $testAdminPermission;

    protected $useCustomModels = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->configureDatabase($this->app);

        $this->testUser = User::first();
        $this->testUserGroup = app(GroupContract::class)->first();
        $this->testUserPermission = app(PermissionContract::class)->first();

        $this->testAdmin = Admin::first();
        $this->testAdminGroup = app(GroupContract::class)->find(3);
        $this->testAdminPermission = app(PermissionContract::class)->find(4);

        $this->setUpRoutes();
    }

    public function getPackageProviders($app): array
    {
        return [
            ACLServiceProvider::class,
            ACLAuthServiceProvider::class,
            ACLEventsServiceProvider::class,
            IgnitionServiceProvider::class,
            ACLViewServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('acl.register_permission_check_method', true);
        $app['config']->set('acl.testing', true);
        $app['config']->set('acl.column_names.model_morph_key', 'model_test_id');


        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('acl.column_names.role_pivot_key', 'role_test_id');
        $app['config']->set('acl.column_names.permission_pivot_key', 'permission_test_id');

        $app['config']->set('views.path', [__DIR__.'/resources/views']);
        $app['config']->set('auth.guards.api', ['driver' => 'session', 'provider' => 'users']);

        $app['config']->set('auth.guards.admin', ['driver' => 'session', 'provider' => 'admins']);
        $app['config']->set('auth.providers.admins', ['driver' => 'eloquent', 'model' => Admin::class]);

        if ($this->useCustomModels) {
            $app['config']->set('acl.models.permission', Permission::class);
            $app['config']->set('acl.models.role', Group::class);
        }

        // Use test model for users provider
        $app['config']->set('auth.providers.users.model', User::class);

        // Make sure the ignition integration does register correctly
        $app['config']->set('acl.offer_solutions', true);

        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('cache.prefix', 'acl_tests---');
    }

    /**
     * Set up the database for tests.
     * @param $app
     */
    public function configureDatabase($app)
    {
        $app['config']->set('acl.tables.users', 'users');
        $app['config']->set('acl.tables.groups', 'groups');
        $app['config']->set('acl.tables.permissions', 'permissions');
        $app['config']->set('acl.tables.model_has_permissions', 'model_has_permissions');
        $app['config']->set('acl.tables.group_has_permissions', 'group_has_permissions');
        $app['config']->set('acl.tables.model_has_groups', 'model_has_groups');

        $app['config']->set('group_pivot_key', null);
        $app['config']->set('permission_pivot_key', null);
        $app['config']->set('model_morph_key', 'model_id');

        $app['config']->set('acl.models.permission', Permission::class);
        $app['config']->set('acl.models.group', Group::class);
        $app['config']->set('acl.models.user', User::class);

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email');
            $table->softDeletes();
        });

        $app['db']->connection()->getSchemaBuilder()->create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
        });

        if (Cache::getStore() instanceof DatabaseStore
            || $app[AclRegistrar::class]->getCacheStore() instanceof DatabaseStore) {
            $this->createCacheTable();
        }

        include_once __DIR__ . '/../database/migrations/create_permissions_table.php';
        include_once __DIR__ . '/../database/migrations/create_groups_table.php';
        include_once __DIR__ . '/../database/migrations/create_group_has_permissions_table.php';
        include_once __DIR__ . '/../database/migrations/create_model_has_permissions_table.php';
        include_once __DIR__ . '/../database/migrations/create_model_has_groups_table.php';

        (new CreatePermissionsTable())->up();
        (new CreateGroupsTable())->up();
        (new CreateGroupHasPermissionsTable())->up();
        (new CreateModelHasPermissionsTable())->up();
        (new CreateModelHasGroupsTable())->up();

        User::create(['email' => 'test@user.com',]);
        Admin::create(['email' => 'admin@user.com']);

        $app[GroupContract::class]->create(['name' => 'testGroup']);
        $app[GroupContract::class]->create(['name' => 'testGroup2']);
        $app[GroupContract::class]->create(['name' => 'testAdminRole', 'guard_name' => 'admin']);
        $app[PermissionContract::class]->create(['name' => 'edit-articles']);
        $app[PermissionContract::class]->create(['name' => 'edit-news']);
        $app[PermissionContract::class]->create(['name' => 'edit-blog']);
        $app[PermissionContract::class]->create(['name' => 'admin-permission', 'guard_name' => 'admin']);
        $app[PermissionContract::class]->create(['name' => 'Delete News']);
    }

    public function createCacheTable()
    {
        Schema::create('cache', function ($table) {
            $table->string('key')->unique();
            $table->text('value');
            $table->integer('expiration');
        });
    }

    public function setUpRoutes(): void
    {
//        Route::middleware('auth:api')->get('/check-api-guard-permission', function (Request $request) {
//            return [
//                'status' => $request->user()->hasPermission('do_that'),
//            ];
//        });
    }

    protected function reloadPermissions()
    {
        app(AclRegistrar::class)->forgetCachedPermissions();
    }
}
