<?php

namespace Junges\Tests\Exceptions;

use Illuminate\Database\QueryException;
use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class ExceptionsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function throws_exception_when_creating_duplicate_permissions()
    {
        $this->expectException(QueryException::class);
        Permission::create([
            'slug' => 'test-duplicate-permission-one',
            'name' => 'Test duplicate Permision one',
            'description' => 'This is a test description',
        ]);
        Permission::create([
            'slug' => 'test-duplicate-permission-one',
            'name' => 'Test duplicate Permision one',
            'description' => 'This is a test description',
        ]);
    }
}
