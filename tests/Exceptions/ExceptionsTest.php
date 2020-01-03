<?php

namespace Junges\ACL\Tests\Exceptions;

use Illuminate\Database\QueryException;
use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\Permission;
use Junges\ACL\Tests\TestCase;

class ExceptionsTest extends TestCase
{
    public function setUp(): void
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

    /**
     * @test
     */
    public function throws_an_exception_when_the_group_already_exists()
    {
        $this->expectException(QueryException::class);
        Group::create([
            'name' => 'Test Group',
            'slug' => 'test-group',
            'description' => 'This is a test group',
        ]);
        Group::create([
            'name' => 'Test Group',
            'slug' => 'test-group',
            'description' => 'This is a test group',
        ]);
    }
}
