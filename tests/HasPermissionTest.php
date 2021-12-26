<?php

namespace Junges\ACL\Tests;

use Illuminate\Support\Facades\DB;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Contracts\Permission as PermissionContract;
use Junges\ACL\Exceptions\GuardDoesNotMatch;
use Junges\ACL\Exceptions\PermissionDoesNotExistException;
use stdClass;

class HasPermissionTest extends TestCase
{
    public function testItCanAssignAPermissionToUser()
    {
        $this->testUser->assignPermission($this->testUserPermission);

        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission));
    }
    
    public function testItThrowsAnExceptionWhenAssigningAPermissionThatDoesNotExist()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUser->assignPermission('permission-does-not-exist');
    }

    public function testItThrowsAnExceptionWhenAssigningAPermissionToAUserFromADifferentGuard()
    {
        $this->expectException(GuardDoesNotMatch::class);

        $this->testUser->assignPermission($this->testAdminPermission);

        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUser->assignPermission('admin-permission');
    }

    public function testICanRevokeAPermissionFromAUser()
    {
        $this->testUser->assignPermission($this->testUserPermission);

        $this->assertTrue($this->testUser->hasPermission($this->testUserPermission));

        $this->testUser->revokePermission($this->testUserPermission);

        $this->assertFalse($this->testUser->hasPermission($this->testUserPermission));
    }

    public function testItCanScopeUsersUsingAString()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);

        $user1->assignPermission(['edit-articles', 'edit-news']);

        $this->testUserGroup->assignPermission('edit-articles');

        $user2->assignGroup('testGroup');

        $scopedUsers1 = User::permission('edit-articles')->get();
        $scopedUsers2 = User::permission(['edit-news'])->get();

        $this->assertEquals(2, $scopedUsers1->count());
        $this->assertEquals(1, $scopedUsers2->count());
    }

    public function testItCanScopeUsersUsingAInt()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignPermission([1, 2]);
        $this->testUserGroup->assignPermission(1);
        $user2->assignGroup('testGroup');

        $scopedUsers1 = User::permission(1)->get();
        $scopedUsers2 = User::permission([2])->get();

        $this->assertEquals(2, $scopedUsers1->count());
        $this->assertEquals(1, $scopedUsers2->count());
    }

    public function testItCanScopeUsersUsingAnArray()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignPermission(['edit-articles', 'edit-news']);
        $this->testUserGroup->assignPermission('edit-articles');
        $user2->assignGroup('testGroup');

        $scopedUsers1 = User::permission(['edit-articles', 'edit-news'])->get();
        $scopedUsers2 = User::permission(['edit-news'])->get();

        $this->assertEquals(2, $scopedUsers1->count());
        $this->assertEquals(1, $scopedUsers2->count());
    }

    public function testItCanScopeUsersUsingACollection()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignPermission(['edit-articles', 'edit-news']);
        $this->testUserGroup->assignPermission('edit-articles');
        $user2->assignGroup('testGroup');

        $scopedUsers1 = User::permission(collect(['edit-articles', 'edit-news']))->get();
        $scopedUsers2 = User::permission(collect(['edit-news']))->get();

        $this->assertEquals(2, $scopedUsers1->count());
        $this->assertEquals(1, $scopedUsers2->count());
    }

    public function testItCanScopeUsersUsingAnObject()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user1->assignPermission($this->testUserPermission->name);

        $scopedUsers1 = User::permission($this->testUserPermission)->get();
        $scopedUsers2 = User::permission([$this->testUserPermission])->get();
        $scopedUsers3 = User::permission(collect([$this->testUserPermission]))->get();

        $this->assertEquals(1, $scopedUsers1->count());
        $this->assertEquals(1, $scopedUsers2->count());
        $this->assertEquals(1, $scopedUsers3->count());
    }

    public function testItCanScopeUsersWithoutPermissionsOnlyGroup()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $this->testUserGroup->assignPermission('edit-articles');
        $user1->assignGroup('testGroup');
        $user2->assignGroup('testGroup');

        $scopedUsers = User::permission('edit-articles')->get();

        $this->assertEquals(2, $scopedUsers->count());
    }

    public function testItCanScopeUsersWithoutPermissionsOnlyPermission()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignPermission(['edit-news']);
        $user2->assignPermission(['edit-articles', 'edit-news']);

        $scopedUsers = User::permission('edit-news')->get();

        $this->assertEquals(2, $scopedUsers->count());
    }

    public function testItThrowsAnExceptionWhenCallingHasPermissionWithAnInvalidType()
    {
        $user = User::create(['email' => 'user1@test.com']);

        $this->expectException(PermissionDoesNotExistException::class);

        $user->hasPermission(new stdClass());
    }

    public function testItThrowsAnExceptionWhenCallingHasPermissionWithNull()
    {
        $user = User::create(['email' => 'user1@test.com']);

        $this->expectException(PermissionDoesNotExistException::class);

        $user->hasPermission(null);
    }

    public function testItThrowsAnExceptionWhenCallingHasDirectPermissionWithInvalidType()
    {
        $user = User::create(['email' => 'user1@test.com']);

        $this->expectException(PermissionDoesNotExistException::class);

        $user->hasDirectPermission(new stdClass());
    }

    public function testItThrowsAnExceptionWhenCallingHasDirectPermissionWithNull()
    {
        $user = User::create(['email' => 'user1@test.com']);

        $this->expectException(PermissionDoesNotExistException::class);

        $user->hasDirectPermission(null);
    }

    public function testItThrowsAnExceptionWhenTryingToScopeANonExistingPermission()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        User::permission('not defined permission')->get();
    }

    public function testItThrowsAnExceptionWhenTryingToScopeAPermissionFromAnotherGuard()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        User::permission('testAdminPermission')->get();

        $this->expectException(GuardDoesNotMatch::class);

        User::permission($this->testAdminPermission)->get();
    }

    public function testItCanScopeGroupsWithId()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user1->assignGroup($this->testUserGroup);

        $scopedUsers1 = User::group($this->testUserGroup)->get();
        $scopedUsers2 = User::group([$this->testUserGroup])->get();
        $scopedUsers3 = User::group(collect([$this->testUserGroup]))->get();

        $this->assertEquals(1, $scopedUsers1->count());
        $this->assertEquals(1, $scopedUsers2->count());
        $this->assertEquals(1, $scopedUsers3->count());
    }

    public function testItDoesNotDetachPermissionsWhenSoftDeleting()
    {
        $user = SoftDeletingUser::create(['email' => 'test@example.com']);
        $user->assignPermission(['edit-news']);
        $user->delete();

        $user = SoftDeletingUser::withTrashed()->find($user->id);

        $this->assertTrue($user->hasPermission('edit-news'));
    }

    public function testItCanGiveAndRevokeMultiplePermissions()
    {
        $this->testUserGroup->assignPermission(['edit-articles', 'edit-news']);

        $this->assertEquals(2, $this->testUserGroup->permissions()->count());

        $this->testUserGroup->revokePermission(['edit-articles', 'edit-news']);

        $this->assertEquals(0, $this->testUserGroup->permissions()->count());
    }

    public function testItCanDetermineThatTheUserDoesNotHaveAPermission()
    {
        $this->assertFalse($this->testUser->hasPermission('edit-articles'));
    }

    public function testItThrowsAnExceptionWhenThePermissionDoesNotExist()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUser->hasPermission('does-not-exist');
    }

    public function testItThrowsAnExceptionWhenThePermissionDoesNotExistForThisGuard()
    {
        $this->expectException(PermissionDoesNotExistException::class);

        $this->testUser->hasPermission('does-not-exist', 'web');
    }

    public function testItCanRejectAUserThatDoesNotHaveAnyPermissionsAtAll()
    {
        $user = new User();

        $this->assertFalse($user->hasPermission('edit-articles'));
    }

    public function testItCanDetermineIfUserHasAnyOfThePermissionsDirectly()
    {
        $this->assertFalse($this->testUser->hasAnyPermission('edit-articles'));

        $this->testUser->assignPermission('edit-articles');

        $this->assertTrue($this->testUser->hasAnyPermission('edit-news', 'edit-articles'));

        $this->testUser->assignPermission('edit-news');

        $this->testUser->revokePermission($this->testUserPermission);

        $this->assertTrue($this->testUser->hasAnyPermission('edit-articles', 'edit-news'));
        $this->assertFalse($this->testUser->hasAnyPermission('edit-blog', 'Edit News', ['Edit News']));
    }

    public function testItCanDetermineThatUserHasAnyOfThePermissionsDirectlyUsingAnArray()
    {
        $this->assertFalse($this->testUser->hasAnyPermission(['edit-articles']));

        $this->testUser->assignPermission('edit-articles');

        $this->assertTrue($this->testUser->hasAnyPermission(['edit-news', 'edit-articles']));

        $this->testUser->assignPermission('edit-news');

        $this->testUser->revokePermission($this->testUserPermission);

        $this->assertTrue($this->testUser->hasAnyPermission(['edit-articles', 'edit-news']));
    }

    public function testItCanDetermineThatUserHasAnyOfThePermissionsViaGroup()
    {
        $this->testUserGroup->assignPermission('edit-articles');

        $this->testUser->assignGroup('testGroup');

        $this->assertTrue($this->testUser->hasAnyPermission('edit-news', 'edit-articles'));
        $this->assertFalse($this->testUser->hasAnyPermission('edit-blog', 'Edit News', ['Edit News']));
    }

    public function testItCanDetermineThatUserHasAllOfThePermissionsDirectly()
    {
        $this->testUser->assignPermission('edit-articles', 'edit-news');

        $this->assertTrue($this->testUser->hasAllPermissions('edit-articles', 'edit-news'));

        $this->testUser->revokePermission('edit-articles');

        $this->assertFalse($this->testUser->hasAllPermissions('edit-articles', 'edit-news'));
        $this->assertFalse($this->testUser->hasAllPermissions(['edit-articles', 'edit-news'], 'edit-blog'));
    }

    public function testItCanDetermineIfUserHasAllOfThePermissionsDirectlyUsingAnArray()
    {
        $this->assertFalse($this->testUser->hasAllPermissions(['edit-articles', 'edit-news']));

        $this->testUser->revokePermission('edit-articles');

        $this->assertFalse($this->testUser->hasAllPermissions(['edit-news', 'edit-articles']));

        $this->testUser->assignPermission('edit-news');

        $this->testUser->revokePermission($this->testUserPermission);

        $this->assertFalse($this->testUser->hasAllPermissions(['edit-articles', 'edit-news']));
    }

    public function testItCanDetermineIfUserHasAllOfThePermissionsViaGroups()
    {
        $this->testUserGroup->assignPermission('edit-articles', 'edit-news');

        $this->testUser->assignGroup('testGroup');

        $this->assertTrue($this->testUser->hasAllPermissions('edit-articles', 'edit-news'));
    }

    public function testItCanDetermineIfUserHasDirectPermission()
    {
        $this->testUser->assignPermission('edit-articles');
        $this->assertTrue($this->testUser->hasDirectPermission('edit-articles'));
        $this->assertEquals(
            collect(['edit-articles']),
            $this->testUser->getDirectPermissions()->pluck('name')
        );

        $this->testUser->revokePermission('edit-articles');
        $this->assertFalse($this->testUser->hasDirectPermission('edit-articles'));

        $this->testUser->assignGroup('testGroup');
        $this->testUserGroup->assignPermission('edit-articles');
        $this->assertFalse($this->testUser->hasDirectPermission('edit-articles'));
    }

    public function testItCanListAllPermissionsViaGroupsOfUser()
    {
        $groupModel = app(GroupContract::class);
        $groupModel->findByName('testGroup2')->assignPermission('edit-news');

        $this->testUserGroup->assignPermission('edit-articles');
        $this->testUser->assignGroup('testGroup', 'testGroup2');

        $this->assertEquals(
            collect(['edit-articles', 'edit-news']),
            $this->testUser->getPermissionsViaGroups()->pluck('name')
        );
    }

    public function testItCanListAllTheCoupledPermissionsBothDirectlyAndViaGroups()
    {
        $this->testUser->assignPermission('edit-news');

        $this->testUserGroup->assignPermission('edit-articles');
        $this->testUser->assignGroup('testGroup');

        $this->assertEquals(
            collect(['edit-articles', 'edit-news']),
            $this->testUser->getAllPermissions()->pluck('name')->sort()->values()
        );
    }
    
    public function testItCanSyncMultiplePermissions()
    {
        $this->testUser->assignPermission('edit-news');

        $this->testUser->syncPermissions('edit-articles', 'edit-blog');

        $this->assertTrue($this->testUser->hasDirectPermission('edit-articles'));

        $this->assertTrue($this->testUser->hasDirectPermission('edit-blog'));

        $this->assertFalse($this->testUser->hasDirectPermission('edit-news'));
    }
    
    public function testItCanSyncMultiplePermissionsById()
    {
        $this->testUser->assignPermission('edit-news');

        $ids = app(PermissionContract::class)::whereIn('name', ['edit-articles', 'edit-blog'])->pluck($this->testUserPermission->getKeyName());

        $this->testUser->syncPermissions($ids);

        $this->assertTrue($this->testUser->hasDirectPermission('edit-articles'));

        $this->assertTrue($this->testUser->hasDirectPermission('edit-blog'));

        $this->assertFalse($this->testUser->hasDirectPermission('edit-news'));
    }
    
    public function testSyncPermissionIgnoresNullInputs()
    {
        $this->testUser->assignPermission('edit-news');

        $ids = app(PermissionContract::class)::whereIn('name', ['edit-articles', 'edit-blog'])->pluck($this->testUserPermission->getKeyName());

        $ids->push(null);

        $this->testUser->syncPermissions($ids);

        $this->assertTrue($this->testUser->hasDirectPermission('edit-articles'));

        $this->assertTrue($this->testUser->hasDirectPermission('edit-blog'));

        $this->assertFalse($this->testUser->hasDirectPermission('edit-news'));
    }

    public function testItDoesNotRemoveAlreadyAssociatedPermissionsWhenAssigningNewPermissions()
    {
        $this->testUser->assignPermission('edit-news');

        $this->testUser->assignPermission('edit-articles');

        $this->assertTrue($this->testUser->fresh()->hasDirectPermission('edit-news'));
    }
    
    public function testItDoesNotThrowAnExceptionWhenAssigningAPermissionThatIsAlreadyAssigned()
    {
        $this->testUser->assignPermission('edit-news');

        $this->testUser->assignPermission('edit-news');

        $this->assertTrue($this->testUser->fresh()->hasDirectPermission('edit-news'));
    }
    
    public function testItCanSyncPermissionsToAModelThatIsNotPersisted()
    {
        $user = new User(['email' => 'test@user.com']);
        $user->syncPermissions('edit-articles');
        $user->save();

        $this->assertTrue($user->hasPermission('edit-articles'));

        $user->syncPermissions('edit-articles');
        $this->assertTrue($user->hasPermission('edit-articles'));
        $this->assertTrue($user->fresh()->hasPermission('edit-articles'));
    }
    
    public function testCallingAssignPermissionBeforeSavingObjectDoesntInterfereWithOtherObjects()
    {
        $user = new User(['email' => 'test@user.com']);
        $user->assignPermission('edit-news');
        $user->save();

        $user2 = new User(['email' => 'test2@user.com']);
        $user2->assignPermission('edit-articles');

        DB::enableQueryLog();
        $user2->save();
        $querys = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertTrue($user->fresh()->hasPermission('edit-news'));
        $this->assertFalse($user->fresh()->hasPermission('edit-articles'));

        $this->assertTrue($user2->fresh()->hasPermission('edit-articles'));
        $this->assertFalse($user2->fresh()->hasPermission('edit-news'));
        $this->assertSame(4, count($querys)); //avoid unnecessary sync
    }
    
    public function testCallingSyncPermissionsBeforeSavingObjectDoesntInterfereWithOtherObjects()
    {
        $user = new User(['email' => 'test@user.com']);
        $user->syncPermissions('edit-news');
        $user->save();

        $user2 = new User(['email' => 'test2@user.com']);
        $user2->syncPermissions('edit-articles');

        DB::enableQueryLog();
        $user2->save();
        $querys = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertTrue($user->fresh()->hasPermission('edit-news'));
        $this->assertFalse($user->fresh()->hasPermission('edit-articles'));

        $this->assertTrue($user2->fresh()->hasPermission('edit-articles'));
        $this->assertFalse($user2->fresh()->hasPermission('edit-news'));
        $this->assertSame(4, count($querys)); //avoid unnecessary sync
    }

    public function testItCanRetrievePermissionNames()
    {
        $this->testUser->assignPermission('edit-news', 'edit-articles');
        $this->assertEquals(
            collect(['edit-articles', 'edit-news']),
            $this->testUser->getPermissionNames()->sort()->values()
        );
    }
    
    public function testItCanCheckManyDirectPermissions()
    {
        $this->testUser->assignPermission(['edit-articles', 'edit-news']);
        $this->assertTrue($this->testUser->hasAllDirectPermissions(['edit-news', 'edit-articles']));
        $this->assertTrue($this->testUser->hasAllDirectPermissions('edit-news', 'edit-articles'));
        $this->assertFalse($this->testUser->hasAllDirectPermissions(['edit-articles', 'edit-news', 'edit-blog']));
        $this->assertFalse($this->testUser->hasAllDirectPermissions(['edit-articles', 'edit-news'], 'edit-blog'));
    }
    
    public function testItCanCheckIfThereIsAnyOfTheDirectPermissionsGiven()
    {
        $this->testUser->assignPermission(['edit-articles', 'edit-news']);
        $this->assertTrue($this->testUser->hasAnyDirectPermission(['edit-news', 'edit-blog']));
        $this->assertTrue($this->testUser->hasAnyDirectPermission('edit-news', 'edit-blog'));
        $this->assertFalse($this->testUser->hasAnyDirectPermission('edit-blog', 'Delete News', ['Delete News']));
    }
    
    public function testItCanCheckPermissionBasedOnLoggedInUserGuard()
    {
        $this->testUser->assignPermission(app(PermissionContract::class)::create([
            'name' => 'do_that',
            'guard_name' => 'api',
        ]));
        
        $response = $this->actingAs($this->testUser, 'api')->json('GET', '/check-api-guard-permission');
        
        $response->assertJson([
            'status' => true,
        ]);
    }
    
    public function testItCanRejectPermissionBasedOnLoggedInUserGuard()
    {
        $unassignedPermission = app(PermissionContract::class)::create([
            'name' => 'do_that',
            'guard_name' => 'api',
        ]);

        $assignedPermission = app(PermissionContract::class)::create([
            'name' => 'do_that',
            'guard_name' => 'web',
        ]);

        $this->testUser->assignPermission($assignedPermission);
        $response = $this->withExceptionHandling()
            ->actingAs($this->testUser, 'api')
            ->json('GET', '/check-api-guard-permission');
        $response->assertJson([
            'status' => false,
        ]);
    }
}
