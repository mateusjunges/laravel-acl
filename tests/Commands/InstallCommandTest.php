<?php

namespace Junges\ACL\Tests\Commands;

use Junges\ACL\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    private $migrationsPath = 'migrations/vendor/junges/acl';

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function test_installation_command()
    {
        $this->artisan('acl:install');
        $this->assertFileExists(config_path('acl.php'));
        $this->assertFileExists(database_path($this->migrationsPath.'/2019_03_16_005237_create_permissions_table.php'));
        $this->assertFileExists(database_path($this->migrationsPath.'/2019_03_16_005538_create_user_has_permissions_table.php'));
        $this->assertFileExists(database_path($this->migrationsPath.'/2019_03_16_005634_create_groups_table.php'));
        $this->assertFileExists(database_path($this->migrationsPath.'/2019_03_16_005759_create_group_has_permissions_table.php'));
        $this->assertFileExists(database_path($this->migrationsPath.'/2019_03_16_005834_create_user_has_groups_table.php'));
    }
}
