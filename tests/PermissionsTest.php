<?php


namespace Junges\ACL\Test;


use Illuminate\Database\QueryException;

class PermissionsTest extends TestCase
{
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
