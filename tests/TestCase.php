<?php

namespace Junges\ACL\Tests;

use Facade\Ignition\IgnitionServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Junges\ACL\ACLAuthServiceProvider;
use Junges\ACL\ACLServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @var User
     */
    protected $testUser;

    /**
     * @var User
     */
    protected $testUser2;

    /**
     * @var User
     */
    protected $testUser3;

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
    protected $testUserPermission2;

    /**
     * @var Permission
     */
    protected $testUserPermission3;

    /**
     * @var Permission
     */
    protected $testAdminPermission;

    /**
     * @var User
     */
    protected $testAdminUser;

    /**
     * @var Group
     */
    protected $testUserGroup2;

    /**
     * Set up the tests.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->configureDatabase($this->app);

        $this->testUser = User::find(1);
        $this->testAdminUser = User::find(2);
        $this->testUser2 = User::find(3);
        $this->testUser3 = User::find(4);
        $this->testUserGroup = app(Group::class)->find(1);
        $this->testAdminPermission = app(Permission::class)->find(1);
        $this->testUserPermission = app(Permission::class)->find(2);
        $this->testUserPermission2 = app(Permission::class)->find(3);
        $this->testUserPermission3 = app(Permission::class)->find(4);
        $this->testAdminGroup = app(Group::class)->find(2);
        $this->testUserGroup2 = app(Group::class)->find(3);

        (new ACLAuthServiceProvider($this->app))->boot();
    }

    public function getPackageProviders($app)
    {
        return [
            ACLServiceProvider::class,
            ACLAuthServiceProvider::class,
            IgnitionServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('views.path', [__DIR__.'/resources/views']);

        // Use test model for users provider
        $app['config']->set('auth.providers.users.model', \Junges\ACL\Tests\User::class);

        // Make sure the ignition integration does register correctly
        $app['config']->set('acl.offer_solutions', true);

        // Set the default admin permission
        $app['config']->set('acl.admin_permission', 'admin');
    }

    /**
     * Set up the database for tests.
     * @param $app
     */
    public function configureDatabase($app)
    {
        /*
         * Set up the tables for testing proposes
         */
        $app['config']->set('acl.tables.users', 'test_users');
        $app['config']->set('acl.tables.groups', 'test_groups');
        $app['config']->set('acl.tables.permissions', 'test_permissions');
        $app['config']->set('acl.tables.user_has_permissions', 'test_user_has_permissions');
        $app['config']->set('acl.tables.group_has_permissions', 'test_group_has_permissions');
        $app['config']->set('acl.tables.user_has_groups', 'test_user_has_groups');

        /*
         * Set up the models for testing proposes
         */
        $app['config']->set('acl.models.permission', \Junges\ACL\Tests\Permission::class);
        $app['config']->set('acl.models.group', \Junges\ACL\Tests\Group::class);
        $app['config']->set('acl.models.user', \Junges\ACL\Tests\User::class);

        $app['db']->connection()->getSchemaBuilder()->create('test_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->softDeletes();
        });

        /**
         * Include de migration files.
         */
        include_once __DIR__.'/../src/database/migrations/2019_03_16_005237_create_permissions_table.php';
        include_once __DIR__.'/../src/database/migrations/2019_03_16_005634_create_groups_table.php';
        include_once __DIR__.'/../src/database/migrations/2019_03_16_005759_create_group_has_permissions_table.php';
        include_once __DIR__.'/../src/database/migrations/2019_03_16_005538_create_user_has_permissions_table.php';
        include_once __DIR__.'/../src/database/migrations/2019_03_16_005834_create_user_has_groups_table.php';

        /*
         * Create the tables on the database
         */
        (new \CreatePermissionsTable())->up();
        (new \CreateGroupsTable())->up();
        (new \CreateGroupHasPermissionsTable())->up();
        (new \CreateUserHasPermissionsTable())->up();
        (new \CreateUserHasGroupsTable())->up();

        /*
         * Create some new users
         */
        User::create([
            'name' => 'User 1',
            'email' => 'user@user.com',
        ]);
        User::create([
            'name' => 'User 2',
            'email' => 'admin@admin.com',
        ]);
        User::create([
            'name' => 'User 3',
            'email' => 'user3@user3.com',
        ]);
        User::create([
            'name' => 'User 4',
            'email' => 'user4@user4.com',
        ]);
        /*
         * Create some groups
         */
        Group::create([
            'name' => 'Test User Group',
            'slug' => 'test-user-group',
            'description' => 'This is the test user group',
        ]);
        Group::create([
            'name' => 'Test Admin Group',
            'slug' => 'test-admin-group',
            'description' => 'This is the test admin user group',
        ]);
        Group::create([
            'name' => 'Test User Group 2',
            'slug' => 'test-user-group-2',
            'description' => 'This is the test user group 2',
        ]);

        /*
         * Create some permissions
         */
        Permission::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'This permission give you all access to the system',
        ]);
        Permission::create([
            'name' => 'Edit Posts',
            'slug' => 'edit-posts',
            'description' => 'This permission allows you to edit posts',
        ]);
        Permission::create([
            'name' => 'Edit Articles',
            'slug' => 'edit-articles',
            'description' => 'This permission allows you to edit articles',
        ]);
        Permission::create([
            'name' => 'Edit website',
            'slug' => 'edit-website',
            'description' => 'This permission allows you to edit the website',
        ]);
        Permission::create([
            'name' => 'Edit news',
            'slug' => 'edit-news',
            'description' => 'This permission allows you to edit the news page',
        ]);
        Permission::create([
            'name' => 'Test hierarchical permissions',
            'slug' => 'admin.auth',
            'description' => 'This is a hierarchical permission test',
        ]);
        Permission::create([
            'name' => 'Test hierarchical permissions 1',
            'slug' => 'admin.auth.users',
            'description' => 'This is a hierarchical permission test',
        ]);
    }
}
