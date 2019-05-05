<?php


namespace Junges\ACL\Test;

use Illuminate\Database\Schema\Blueprint;
use Junges\ACL\ACLAuthServiceProvider;
use Junges\ACL\ACLServiceProvider;
use Junges\ACL\Http\Models\Group;
use Junges\ACL\Http\Models\Permission;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @var User
     */
    protected $testUser;
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
    /**
     * @var User
     */
    protected $testAdminUser;

    /**
     * Set up the tests
     */

    public function setUp()
    {
        parent::setUp();

        $this->configureDatabase($this->app);
        $this->testUser = User::find(1);
        $this->testAdminUser = User::find(2);
        $this->testUserGroup = app(Group::class)->find(1);
        $this->testAdminPermission = app(Permission::class)->find(1);
        $this->testUserPermission = app(Permission::class)->find(2);
        $this->testAdminGroup = app(Group::class)->find(2);


    }

    public function getPackageProviders($app)
    {
        return [
            ACLServiceProvider::class,
            ACLAuthServiceProvider::class,
        ];
    }

    /**
     * Set up the environment
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'pgsql');
        $app['config']->set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'package_test'),
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', '16021826'),
        ]);
        $app['config']->set('views.path', [__DIR__.'/resources/views']);

        // Use test model for users provider
        $app['config']->set('auth.providers.users.model', \Junges\ACL\Test\User::class);

    }

    /**
     * Set up the database for tests
     * @param $app
     */
    public function configureDatabase($app)
    {
        $app['config']->set('acl.tables.users', 'users');

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table){
           $table->bigIncrements('id');
           $table->string('email');
           $table->softDeletes();
        });

        /**
         * Include de migration files
         */
        include_once __DIR__ . '/../src/database/migrations/2019_03_16_005237_create_permissions_table.php';
        include_once __DIR__ . '/../src/database/migrations/2019_03_16_005634_create_groups_table.php';
        include_once __DIR__ . '/../src/database/migrations/2019_03_16_005759_create_group_has_permissions_table.php';
        include_once __DIR__ . '/../src/database/migrations/2019_03_16_005538_create_user_has_permissions_table.php';
        include_once __DIR__ . '/../src/database/migrations/2019_03_16_005834_create_user_has_groups_table.php';

        /**
         * Create the tables on the database
         */
        (new \CreatePermissionsTable())->up();
        (new \CreateGroupsTable())->up();
        (new \CreateGroupHasPermissionsTable())->up();


        /**
         * Create two new users
         */
        User::create([
           'email' => 'user@user.com',
        ]);
        User::create([
            'email' => 'admin@admin.com',
        ]);
        /**
         * Create some groups
         */
        $app[Group::class]->create([
           'name' => 'Test User Group',
           'slug' => 'test-user-group',
           'description' => 'This is the test user group'
        ]);
        $app[Group::class]->create([
            'name' => 'Test Admin Group',
            'slug' => 'test-admin-group',
            'description' => 'This is the test admin user group',
        ]);

        /**
         * Create some permissions
         */
        $app[Permission::class]->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'This permission give you all access to the system',
        ]);
        $app[Permission::class]->create([
           'name' => 'Edit Posts',
           'slug' => 'edit-posts',
           'description' => 'This permission allows you to edit posts',
        ]);
        $app[Permission::class]->create([
            'name' => 'Edit Articles',
            'slug' => 'edit-articles',
            'description' => 'This permission allows you to edit articles',
        ]);
        $app[Permission::class]->create([
            'name' => 'Edit website',
            'slug' => 'edit-website',
            'description' => 'This permission allows you to edit the website',
        ]);
        $app[Permission::class]->create([
            'name' => 'Edit news',
            'slug' => 'edit-news',
            'description' => 'This permission allows you to edit the news page',
        ]);

    }
}
