<?php

namespace Junges\ACL\Tests\Commands;

use Junges\ACL\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    public function test_it_can_install_the_package()
    {
        $this->artisan('acl:install')->assertExitCode(0);
    }
}
