<?php

namespace Junges\ACL\Tests\Listeners;

use Junges\ACL\Exceptions\GroupAlreadyExistsException;
use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class GroupSavingEventListenerTest extends TestCase
{
    public function test_it_throws_exception_if_group_already_exists()
    {
        $this->expectException(GroupAlreadyExistsException::class);

        Group::create([
            'name' => 'Test User Group',
            'slug' => 'test-user-group',
            'description' => 'This is the test user group',
        ]);
    }
}
