<?php

namespace Junges\ACL\Tests\Concerns\UsersTrait;

use Junges\ACL\Tests\Group;
use Junges\ACL\Tests\TestCase;

class AssignAllGroupsMethodTest extends TestCase
{
    public function test_it_assigns_all_groups_to_the_user()
    {
        $this->testUser->assignAllGroups();

        $this->assertCount(Group::count(), $this->testUser->refresh()->groups);
    }
}
