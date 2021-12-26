<?php

namespace Junges\ACL\Tests;

use Junges\ACL\AclRegistrar;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Exceptions\GroupAlreadyExistsException;
use Junges\ACL\Exceptions\GroupDoesNotExistException;
use Junges\ACL\Exceptions\GuardDoesNotMatch;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;

class GroupTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'other-permission']);

        Permission::create(['name' => 'wrong-guard-permission', 'guard_name' => 'admin']);
    }
    
    public function testItHasUserModelsOfTheRightClass()
    {
        $this->testAdmin->assignGroup($this->testAdminGroup);

        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertCount(1, $this->testUserGroup->users);
        $this->assertTrue($this->testUserGroup->users->first()->is($this->testUser));
        $this->assertInstanceOf(User::class, $this->testUserGroup->users->first());

        $this->assertCount(1, $this->testAdminGroup->users);
        $this->assertTrue($this->testAdminGroup->users->first()->is($this->testAdmin));
        $this->assertInstanceOf(Admin::class, $this->testAdminGroup->users->first());
    }

    public function testItThrowsExceptionWhenTheGroupAlreadyExists()
    {
        $this->expectException(GroupAlreadyExistsException::class);

        app(GroupContract::class)->create(['name' => 'test-role']);
        app(GroupContract::class)->create(['name' => 'test-role']);
    }
    
    public function testItCanBeGivenAPermission()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->assertTrue($this->testUserGroup->hasPermission('edit-articles'));
    }

    public function testItThrowsAnExceptionWhenAssigningAPermissionThatDoesNotExists()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUserGroup->assignPermission('non existing permission');
    }

    public function testItThrowsAnExceptionWhenGivingAPermissionThatBelongsToAnotherGuard()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUserGroup->assignPermission('admin-permission');

        $this->expectException(GuardDoesNotMatch::class);

        $this->testUserGroup->assignPermission($this->testAdminPermission);
    }

    public function testItCanBeGivenMultiplePermissionsUsingArray()
    {
        $this->testUserGroup->assignPermission(['edit-articles', 'edit-news']);

        $this->assertTrue($this->testUserGroup->hasPermission('edit-articles'));
        $this->assertTrue($this->testUserGroup->hasPermission('edit-news'));
    }
    
    public function testItCanBeGivenMultiplePermissionsUsingMultipleArguments()
    {
        $this->testUserGroup->assignPermission('edit-articles', 'edit-news');

        $this->assertTrue($this->testUserGroup->hasPermission('edit-articles'));
        $this->assertTrue($this->testUserGroup->hasPermission('edit-news'));
    }
    
    public function testItCanSyncPermissions()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->testUserGroup->syncPermissions('edit-news');

        $this->assertFalse($this->testUserGroup->hasPermission('edit-articles'));

        $this->assertTrue($this->testUserGroup->hasPermission('edit-news'));
    }
    
    public function testItThrowsAnExceptionWhenSyncingPermissionsThatDoNotExists()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUserGroup->syncPermissions('permission-does-not-exist');
    }
    
    public function testItThrowsAnExceptionWhenSyncingPermissionsThatBelongsToADifferengGuard()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUserGroup->syncPermissions('admin-permission');

        $this->expectException(GuardDoesNotMatch::class);

        $this->testUserGroup->syncPermissions($this->testAdminPermission);
    }
    
    public function testItWillRemoveAllPermissionsWhenPassingAnEmptyArrayToSyncPermissions()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->testUserGroup->assignPermission('edit-news');

        $this->testUserGroup->syncPermissions([]);

        $this->assertFalse($this->testUserGroup->hasPermission('edit-articles'));

        $this->assertFalse($this->testUserGroup->hasPermission('edit-news'));
    }
    
    public function testItCanRevokeAPermission()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->assertTrue($this->testUserGroup->hasPermission('edit-articles'));

        $this->testUserGroup->revokePermission('edit-articles');

        $this->testUserGroup = $this->testUserGroup->fresh();

        $this->assertFalse($this->testUserGroup->hasPermission('edit-articles'));
    }
    
    public function testItCanBeGivenAPermissinUsingObjects()
    {
        $this->testUserGroup->assignPermission($this->testUserPermission);

        $this->assertTrue($this->testUserGroup->hasPermission($this->testUserPermission));
    }
    
    public function testItReturnsFalseIfItDoesNotHaveThePermission()
    {
        $this->assertFalse($this->testUserGroup->hasPermission('other-permission'));
    }
    
    public function testItThrowsAnExceptionIfThePermissionDoesNotExist()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUserGroup->hasPermission('doesnt-exist');
    }
    
    public function testItReturnsFalseIfItDoewNotHaveAPermissionObject()
    {
        $permission = app(Permission::class)->findByName('other-permission');

        $this->assertFalse($this->testUserGroup->hasPermission($permission));
    }
    
    public function testItCreatesPermissionsObjectWithFindOrCreateIfItDoesNotHaveAPermissionObject()
    {
        $permission = app(Permission::class)->findOrCreate('another-permission');

        $this->assertFalse($this->testUserGroup->hasPermission($permission));

        $this->testUserGroup->assignPermission($permission);

        $this->testUserGroup = $this->testUserGroup->fresh();

        $this->assertTrue($this->testUserGroup->hasPermission('another-permission'));
    }
    
    public function testItCreatesAGroupWithFindOrCreateIfTheNamedGroupDoesNotExist()
    {
        $this->expectException(GroupDoesNotExistException::class);

        $group1 = app(GroupContract::class)->findByName('non-existing-role');

        $this->assertNull($group1);

        $group2 = app(GroupContract::class)->findOrCreate('yet-another-role');

        $this->assertInstanceOf(GroupContract::class, $group2);
    }

    public function testItThrowsAnExceptionWhenAPermissionOfTheWrongGuardIsPassed()
    {
        $this->expectException(GuardDoesNotMatch::class);

        $permission = app(Permission::class)->findByName('wrong-guard-permission', 'admin');

        $this->testUserGroup->hasPermission($permission);
    }

    public function testItBelongsToAGuard()
    {
        $group = app(GroupContract::class)->create(['name' => 'admin', 'guard_name' => 'admin']);

        $this->assertEquals('admin', $group->guard_name);
    }

    public function testItBelongsToTheDefaultGuardByDefault()
    {
        $this->assertEquals(
            $this->app['config']->get('auth.defaults.guard'),
            $this->testUserGroup->guard_name
        );
    }

    public function testItCanChangeGroupClassOnRuntime()
    {
        $group = app(GroupContract::class)->create(['name' => 'test-group-old']);
        $this->assertNotInstanceOf(RuntimeGroup::class, $group);

        $group->assignPermission('edit-articles');

        app('config')->set('permission.models.role', RuntimeGroup::class);
        app()->bind(GroupContract::class, RuntimeGroup::class);
        app(AclRegistrar::class)->setGroupClass(RuntimeGroup::class);

        $permission = app(Permission::class)->findByName('edit-articles');
        $this->assertInstanceOf(RuntimeGroup::class, $permission->groups[0]);
        $this->assertSame('test-group-old', $permission->groups[0]->name);

        $group = app(GroupContract::class)->create(['name' => 'test-group']);
        $this->assertInstanceOf(RuntimeGroup::class, $group);

        $this->testUser->assignGroup('test-group');
        $this->assertTrue($this->testUser->hasGroup('test-group'));
        $this->assertInstanceOf(RuntimeGroup::class, $this->testUser->groups[0]);
        $this->assertSame('test-group', $this->testUser->groups[0]->name);
    }
}
