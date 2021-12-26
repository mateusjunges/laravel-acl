<?php

namespace Junges\ACL\Tests;

class HasPermissionUsingCustomModelsTest extends HasPermissionTest
{
    protected bool $useCustomModels = true;

    public function testItCanUseCustomModels()
    {
        $this->assertSame(Permission::class, get_class($this->testUserPermission));
        $this->assertSame(Group::class, get_class($this->testUserGroup));
    }
}