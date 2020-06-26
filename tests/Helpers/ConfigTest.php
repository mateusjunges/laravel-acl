<?php

namespace Junges\ACL\Tests\Commands;

use Junges\ACL\Tests\TestCase;
use Junges\ACL\Helpers\Config;

class ConfigTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function test_if_get_models_return_correct_values()
    {

        $this->assertEquals(
            'App\User',
            Config::get('models.user')
        );
        $this->assertEquals(
            'App\Http\Controllers\Junges\ACL\Http\Models\Permission',
            Config::get('models.permission')
        );
        $this->assertEquals(
            'Junges\ACL\Http\Models\Group::class',
            Config::get('models.group')
        );
    }
}
