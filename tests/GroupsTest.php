<?php


namespace Junges\ACL\Test;

use Illuminate\Database\QueryException;

class GroupsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
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
             'description' => 'This is a test group'
        ]);
        Group::create([
            'name' => 'Test Group',
            'slug' => 'test-group',
            'description' => 'This is a test group'
        ]);
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_user_model()
    {
        $this->assertIsObject($this->testUserGroup->assignUser([$this->testUser]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_user_id()
    {
        $this->assertIsObject($this->testUserGroup->assignUser([$this->testUser->id]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_user_name()
    {
        $this->assertIsObject($this->testUserGroup->assignUser([$this->testUser->name]));
    }

    /**
     * @test
     */
    public function it_can_be_assigned_to_user_with_mixed_parameters()
    {
        $this->assertIsObject($this->testUserGroup->assignUser(
            [
                $this->testUser,
                $this->testUser2->name,
                $this->testUser3->id,
            ]
        ));
    }
}
