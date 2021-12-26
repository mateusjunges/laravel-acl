<?php

namespace Junges\ACL\Tests;

class HasGroupsWithCustomModelTest extends HasGroupsTest
{
    protected bool $useCustomModels = true;

    public function testItCanUseCustomGroupModel()
    {
        $this->assertSame(get_class($this->testUserGroup), Group::class);
    }
}
