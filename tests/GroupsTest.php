<?php


namespace Junges\ACL\Test;

use Junges\ACL\Exceptions\GroupAlreadyExistsException;
use Junges\ACL\Http\Models\Group;
use Junges\ACL\Http\Models\Permission;

class GroupsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Permission::create([
           'name' => 'Test Permission One',
           'slug' => 'test-permission-one',
           'description' => 'This is a test permission'
        ]);
        Permission::create([
            'name' => 'Test Permission Two',
            'slug' => 'test-permission-two',
            'description' => 'This is another test permission'
        ]);
    }

    /**
     * @test
     */
    public function throws_an_exception_when_the_group_already_exists()
    {
        $this->expectException(GroupAlreadyExistsException::class);
        app(Group::class)->create([
           'name' => 'Test Group',
           'slug' => 'test-group',
           'description' => 'This is a test group'
        ]);
        app(Group::class)->create([
            'name' => 'Test Group',
            'slug' => 'test-group',
            'description' => 'This is a test group'
        ]);
    }
}
