<?php

namespace Junges\ACL\Tests;

use Illuminate\Support\Facades\DB;
use Junges\ACL\Contracts\Group as GroupContract;
use Junges\ACL\Exceptions\GroupDoesNotExistException;
use Junges\ACL\Exceptions\GuardDoesNotMatch;

class HasGroupsTest extends TestCase
{
    public function testItCanDetermineThatTHeUserDoesNotHaveAGroup()
    {
        $this->assertFalse($this->testUser->hasGroup('testGroup'));

        $group = app(GroupContract::class)->findOrCreate('testGroupInWebGuard', 'web');

        $this->assertFalse($this->testUser->hasGroup($group));

        $this->testUser->assignGroup($group);
        $this->assertTrue($this->testUser->hasGroup($group));
        $this->assertTrue($this->testUser->hasGroup($group->name));
        $this->assertTrue($this->testUser->hasGroup($group->name, $group->guard_name));
        $this->assertTrue($this->testUser->hasGroup([$group->name, 'fakeGroup'], $group->guard_name));
        $this->assertTrue($this->testUser->hasGroup($group->getKey(), $group->guard_name));
        $this->assertTrue($this->testUser->hasGroup([$group->getKey(), 'fakeGroup'], $group->guard_name));

        $this->assertFalse($this->testUser->hasGroup($group->name, 'fakeGuard'));
        $this->assertFalse($this->testUser->hasGroup([$group->name, 'fakeGroup'], 'fakeGuard'));
        $this->assertFalse($this->testUser->hasGroup($group->getKey(), 'fakeGuard'));
        $this->assertFalse($this->testUser->hasGroup([$group->getKey(), 'fakeGroup'], 'fakeGuard'));

        $group = app(GroupContract::class)->findOrCreate('testGroupInWebGuard2', 'web');
        $this->assertFalse($this->testUser->hasGroup($group));
    }
    
    public function testItCanAssignAndRemoveGroup()
    {
        $this->assertFalse($this->testUser->hasGroup('testGroup'));

        $this->testUser->assignGroup('testGroup');

        $this->assertTrue($this->testUser->hasGroup('testGroup'));

        $this->testUser->revokeGroup('testGroup');

        $this->assertFalse($this->testUser->hasGroup('testGroup'));
    }
    
    public function testItRemovesAGroupAndReturnGroups()
    {
        $this->testUser->assignGroup('testGroup');

        $this->testUser->assignGroup('testGroup2');

        $this->assertTrue($this->testUser->hasGroup(['testGroup', 'testGroup2']));

        $groups = $this->testUser->revokeGroup('testGroup');

        $this->assertFalse($groups->hasGroup('testGroup'));

        $this->assertTrue($groups->hasGroup('testGroup2'));
    }
    
    public function testItCanAssignAndRemoveAGroupOnAPermission()
    {
        $this->testUserPermission->assignGroup('testGroup');

        $this->assertTrue($this->testUserPermission->hasGroup('testGroup'));

        $this->testUserPermission->revokeGroup('testGroup');

        $this->assertFalse($this->testUserPermission->hasGroup('testGroup'));
    }
    
    public function testItCanAssignAGroupUsingAnObject()
    {
        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));
    }
    
    public function testItCanAssignAGroupUsingAnId()
    {
        $this->testUser->assignGroup($this->testUserGroup->getKey());

        $this->assertTrue($this->testUser->hasGroup($this->testUserGroup));
    }
    
    public function testItCanAssignMultipleGroupsAtOnce()
    {
        $this->testUser->assignGroup($this->testUserGroup->getKey(), 'testGroup2');

        $this->assertTrue($this->testUser->hasGroup('testGroup'));

        $this->assertTrue($this->testUser->hasGroup('testGroup2'));
    }
    
    public function testItCanAssignMultipleGroupsUsingArray()
    {
        $this->testUser->assignGroup([$this->testUserGroup->getKey(), 'testGroup2']);

        $this->assertTrue($this->testUser->hasGroup('testGroup'));

        $this->assertTrue($this->testUser->hasGroup('testGroup2'));
    }
    
    public function testItDoesNotRemoveAlreadyAssociatedGroupsWhenAssignNewGroups()
    {
        $this->testUser->assignGroup($this->testUserGroup->getKey());

        $this->testUser->assignGroup('testGroup2');

        $this->assertTrue($this->testUser->fresh()->hasGroup('testGroup'));
    }
    
    public function testItDoesNotThrowAnExceptionWhenAssigningGroupThatIsAlreadyAssigned()
    {
        $this->testUser->assignGroup($this->testUserGroup->getKey());

        $this->testUser->assignGroup($this->testUserGroup->getKey());

        $this->assertTrue($this->testUser->fresh()->hasGroup('testGroup'));
    }
    
    public function testItThrowsExceptionWHenAssigningAGroupThatDoesNotExist()
    {
        $this->expectException(GroupDoesNotExistException::class);

        $this->testUser->assignGroup('evil-emperor');
    }
    
    public function testItCanOnlyAssignGroupsFromTheCorrectGuard()
    {
        $this->expectException(GroupDoesNotExistException::class);

        $this->testUser->assignGroup('testAdminGroup');
    }
    
    public function testItThrowsAnExceptionWhenAssigningAGroupFromDifferentGuard()
    {
        $this->expectException(GuardDoesNotMatch::class);

        $this->testUser->assignGroup($this->testAdminGroup);
    }
    
    public function testItIgnoresNullGroupsWhenSyncing()
    {
        $this->testUser->assignGroup('testGroup');

        $this->testUser->syncGroups('testGroup2', null);

        $this->assertFalse($this->testUser->hasGroup('testGroup'));

        $this->assertTrue($this->testUser->hasGroup('testGroup2'));
    }

    public function testItCanSyncGroupsFromAString()
    {
        $this->testUser->assignGroup('testGroup');

        $this->testUser->syncGroups('testGroup2');

        $this->assertFalse($this->testUser->hasGroup('testGroup'));

        $this->assertTrue($this->testUser->hasGroup('testGroup2'));
    }

    public function testItCanSyncGroupsFromStringOnAPermission()
    {
        $this->testUserPermission->assignGroup('testGroup');

        $this->testUserPermission->syncGroups('testGroup2');

        $this->assertFalse($this->testUserPermission->hasGroup('testGroup'));

        $this->assertTrue($this->testUserPermission->hasGroup('testGroup2'));
    }

    public function testItCanSyncMultipleGroups()
    {
        $this->testUser->syncGroups('testGroup', 'testGroup2');

        $this->assertTrue($this->testUser->hasGroup('testGroup'));

        $this->assertTrue($this->testUser->hasGroup('testGroup2'));
    }
    
    public function testItCanSyncMultipleGroupsFromAnArray()
    {
        $this->testUser->syncGroups(['testGroup', 'testGroup2']);

        $this->assertTrue($this->testUser->hasGroup('testGroup'));

        $this->assertTrue($this->testUser->hasGroup('testGroup2'));
    }
    
    public function testItWillRemoveAllGroupsWhenAnEmptyArrayIsPassedToSyncGroups()
    {
        $this->testUser->assignGroup('testGroup');

        $this->testUser->assignGroup('testGroup2');

        $this->testUser->syncGroups([]);

        $this->assertFalse($this->testUser->hasGroup('testGroup'));

        $this->assertFalse($this->testUser->hasGroup('testGroup2'));
    }
    
    public function testItWillSyncGroupsToAModelThatIsNotPersisted()
    {
        $user = new User(['email' => 'test@user.com']);
        $user->syncGroups([$this->testUserGroup]);
        $user->save();

        $this->assertTrue($user->hasGroup($this->testUserGroup));
    }
    
    public function testCallingSyncGroupsBeforeSavingObjectDoesNotInterfereWithOtherObjects()
    {
        $user = new User(['email' => 'test@user.com']);
        $user->syncGroups('testGroup');
        $user->save();

        $user2 = new User(['email' => 'admin@user.com']);
        $user2->syncGroups('testGroup2');

        DB::enableQueryLog();
        $user2->save();
        $querys = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertTrue($user->fresh()->hasGroup('testGroup'));
        $this->assertFalse($user->fresh()->hasGroup('testGroup2'));

        $this->assertTrue($user2->fresh()->hasGroup('testGroup2'));
        $this->assertFalse($user2->fresh()->hasGroup('testGroup'));
        $this->assertSame(4, count($querys)); //avoid unnecessary sync
    }
    
    public function calling_assignGroup_before_saving_object_doesnt_interfere_with_other_objects()
    {
        $user = new User(['email' => 'test@user.com']);
        $user->assignGroup('testGroup');
        $user->save();

        $admin_user = new User(['email' => 'admin@user.com']);
        $admin_user->assignGroup('testGroup2');

        DB::enableQueryLog();
        $admin_user->save();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertTrue($user->fresh()->hasGroup('testGroup'));
        $this->assertFalse($user->fresh()->hasGroup('testGroup2'));

        $this->assertTrue($admin_user->fresh()->hasGroup('testGroup2'));
        $this->assertFalse($admin_user->fresh()->hasGroup('testGroup'));
        $this->assertSame(4, count($queries));
    }

    public function testItThrowsAnExceptionWhenSyncingAGroupFromAnotherGuard()
    {
        $this->expectException(GroupDoesNotExistException::class);

        $this->testUser->syncGroups('testGroup', 'testAdminGroup');

        $this->expectException(GuardDoesNotMatch::class);

        $this->testUser->syncGroups('testGroup', $this->testAdminGroup);
    }

    public function testItDeletesPivotTableEntriesWhenDeletingModels()
    {
        $user = User::create(['email' => 'user@test.com']);

        $user->assignGroup('testGroup');
        $user->assignPermission('edit-articles');

        $this->assertDatabaseHas('model_has_permissions', [config('acl.column_names.model_morph_key') => $user->id]);
        $this->assertDatabaseHas('model_has_groups', [config('acl.column_names.model_morph_key') => $user->id]);

        $user->delete();

        $this->assertDatabaseMissing('model_has_permissions', [config('acl.column_names.model_morph_key') => $user->id]);
        $this->assertDatabaseMissing('model_has_groups', [config('acl.column_names.model_morph_key') => $user->id]);
    }
    
    public function testItCanScopeUsersUsingAString()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignGroup('testGroup');
        $user2->assignGroup('testGroup2');

        $scopedUsers = User::group('testGroup')->get();

        $this->assertEquals(1, $scopedUsers->count());
    }

    public function testItCanScopeUsersUsingAnArray()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignGroup($this->testUserGroup);
        $user2->assignGroup('testGroup2');

        $scopedUsers1 = User::group([$this->testUserGroup])->get();

        $scopedUsers2 = User::group(['testGroup', 'testGroup2'])->get();

        $this->assertEquals(1, $scopedUsers1->count());
        $this->assertEquals(2, $scopedUsers2->count());
    }

    public function testItCanScopeUsersUsingAnArrayOfIdsAndNames()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);

        $user1->assignGroup($this->testUserGroup);

        $user2->assignGroup('testGroup2');

        $groupName = $this->testUserGroup->name;

        $othergroupId = app(GroupContract::class)->findByName('testGroup2')->getKey();

        $scopedUsers = User::group([$groupName, $othergroupId])->get();

        $this->assertEquals(2, $scopedUsers->count());
    }

    public function testItCanScopeUsersUsingACollection()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignGroup($this->testUserGroup);
        $user2->assignGroup('testGroup2');

        $scopedUsers1 = User::group([$this->testUserGroup])->get();
        $scopedUsers2 = User::group(collect(['testGroup', 'testGroup2']))->get();

        $this->assertEquals(1, $scopedUsers1->count());
        $this->assertEquals(2, $scopedUsers2->count());
    }

    public function testItCanScopeUsersUsingAnObject()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignGroup($this->testUserGroup);
        $user2->assignGroup('testGroup2');

        $scopedUsers1 = User::group($this->testUserGroup)->get();
        $scopedUsers2 = User::group([$this->testUserGroup])->get();
        $scopedUsers3 = User::group(collect([$this->testUserGroup]))->get();

        $this->assertEquals(1, $scopedUsers1->count());
        $this->assertEquals(1, $scopedUsers2->count());
        $this->assertEquals(1, $scopedUsers3->count());
    }

    public function testItCanScopeAgainstASpecificGuard()
    {
        $user1 = User::create(['email' => 'user1@test.com']);
        $user2 = User::create(['email' => 'user2@test.com']);
        $user1->assignGroup('testGroup');
        $user2->assignGroup('testGroup2');

        $scopedUsers1 = User::group('testGroup', 'web')->get();

        $this->assertEquals(1, $scopedUsers1->count());

        $user3 = Admin::create(['email' => 'user1@test.com']);
        $user4 = Admin::create(['email' => 'user1@test.com']);
        $user5 = Admin::create(['email' => 'user2@test.com']);
        $testAdminGroup2 = app(GroupContract::class)->create(['name' => 'testAdminGroup2', 'guard_name' => 'admin']);
        $user3->assignGroup($this->testAdminGroup);
        $user4->assignGroup($this->testAdminGroup);
        $user5->assignGroup($testAdminGroup2);
        $scopedUsers2 = Admin::group('testAdminGroup', 'admin')->get();
        $scopedUsers3 = Admin::group('testAdminGroup2', 'admin')->get();

        $this->assertEquals(2, $scopedUsers2->count());
        $this->assertEquals(1, $scopedUsers3->count());
    }

    public function testItThrowsAnExceptionWHenTryingToScopeAGroupFromAnotherGuard()
    {
        $this->expectException(GroupDoesNotExistException::class);

        User::group('testAdminGroup')->get();

        $this->expectException(GuardDoesNotMatch::class);

        User::group($this->testAdminGroup)->get();
    }
    
    public function testItThrowsAnExceptionWhenTryingToScopeANonExistingGroup()
    {
        $this->expectException(GroupDoesNotExistException::class);

        User::group('group not defined')->get();
    }

    public function testItCanDetermineThatAUserHasOneOfTheGivenGroups()
    {
        $groupModel = app(GroupContract::class);

        $groupModel->create(['name' => 'second group']);

        $this->assertFalse($this->testUser->hasGroup($groupModel->all()));

        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasGroup($groupModel->all()));

        $this->assertTrue($this->testUser->hasAnygroup($groupModel->all()));

        $this->assertTrue($this->testUser->hasAnygroup('testGroup'));

        $this->assertFalse($this->testUser->hasAnygroup('group does not exist'));

        $this->assertTrue($this->testUser->hasAnygroup(['testGroup']));

        $this->assertTrue($this->testUser->hasAnygroup(['testGroup', 'group does not exist']));

        $this->assertFalse($this->testUser->hasAnygroup(['group does not exist']));

        $this->assertTrue($this->testUser->hasAnygroup('testGroup', 'group does not exist'));
    }

    public function testItCanDetermineThatAUserHasAllOfTheGivenGroups()
    {
        $groupModel = app(GroupContract::class);

        $this->assertFalse($this->testUser->hasAllgroups($groupModel->first()));

        $this->assertFalse($this->testUser->hasAllgroups('testGroup'));

        $this->assertFalse($this->testUser->hasAllgroups($groupModel->all()));

        $groupModel->create(['name' => 'second group']);

        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasAllgroups('testGroup'));
        $this->assertTrue($this->testUser->hasAllgroups('testGroup', 'web'));
        $this->assertFalse($this->testUser->hasAllgroups('testGroup', 'fakeGuard'));

        $this->assertFalse($this->testUser->hasAllgroups(['testGroup', 'second group']));
        $this->assertFalse($this->testUser->hasAllgroups(['testGroup', 'second group'], 'web'));

        $this->testUser->assignGroup('second group');

        $this->assertTrue($this->testUser->hasAllgroups(['testGroup', 'second group']));
        $this->assertTrue($this->testUser->hasAllgroups(['testGroup', 'second group'], 'web'));
        $this->assertFalse($this->testUser->hasAllgroups(['testGroup', 'second group'], 'fakeGuard'));
    }

    public function testItCanDetermineThatAUserHasExactlyAllOfTheGivenGroups()
    {
        $groupModel = app(GroupContract::class);

        $this->assertFalse($this->testUser->hasExactlygroups($groupModel->first()));

        $this->assertFalse($this->testUser->hasExactlygroups('testGroup'));

        $this->assertFalse($this->testUser->hasExactlygroups($groupModel->all()));

        $groupModel->create(['name' => 'second group']);

        $this->testUser->assignGroup($this->testUserGroup);

        $this->assertTrue($this->testUser->hasExactlygroups('testGroup'));
        $this->assertTrue($this->testUser->hasExactlygroups('testGroup', 'web'));
        $this->assertFalse($this->testUser->hasExactlygroups('testGroup', 'fakeGuard'));

        $this->assertFalse($this->testUser->hasExactlygroups(['testGroup', 'second group']));
        $this->assertFalse($this->testUser->hasExactlygroups(['testGroup', 'second group'], 'web'));

        $this->testUser->assignGroup('second group');

        $this->assertTrue($this->testUser->hasExactlygroups(['testGroup', 'second group']));
        $this->assertTrue($this->testUser->hasExactlygroups(['testGroup', 'second group'], 'web'));
        $this->assertFalse($this->testUser->hasExactlygroups(['testGroup', 'second group'], 'fakeGuard'));

        $groupModel->create(['name' => 'third group']);
        $this->testUser->assignGroup('third group');

        $this->assertFalse($this->testUser->hasExactlygroups(['testGroup', 'second group']));
        $this->assertFalse($this->testUser->hasExactlygroups(['testGroup', 'second group'], 'web'));
        $this->assertFalse($this->testUser->hasExactlygroups(['testGroup', 'second group'], 'fakeGuard'));
        $this->assertTrue($this->testUser->hasExactlygroups(['testGroup', 'second group', 'third group']));
        $this->assertTrue($this->testUser->hasExactlygroups(['testGroup', 'second group', 'third group'], 'web'));
        $this->assertFalse($this->testUser->hasExactlygroups(['testGroup', 'second group', 'third group'], 'fakeGuard'));
    }

    public function testItCanDetermineThatAUserDoesNotHaveAGroupFromAnotherGuard()
    {
        $this->assertFalse($this->testUser->hasGroup('testAdminGroup'));

        $this->assertFalse($this->testUser->hasGroup($this->testAdminGroup));

        $this->testUser->assignGroup('testGroup');

        $this->assertTrue($this->testUser->hasAnygroup(['testGroup', 'testAdminGroup']));

        $this->assertFalse($this->testUser->hasAnygroup('testAdminGroup', $this->testAdminGroup));
    }

    public function testItCanCheckAgainstMultipleGroupsUsingMultipleArguments()
    {
        $this->testUser->assignGroup('testGroup');

        $this->assertTrue($this->testUser->hasAnygroup($this->testAdminGroup, ['testGroup'], 'This group Does Not Even Exist'));
    }

    public function testItReturnsFalseInsteadOfAnExceptionWhenCheckingAgainstAnyUndefinedGroupsUsingMultipleArguments()
    {
        $this->assertFalse($this->testUser->hasAnygroup('This group Does Not Even Exist', $this->testAdminGroup));
    }

    public function testItCanRetrieveGroupNames()
    {
        $this->testUser->assignGroup('testGroup', 'testGroup2');

        $this->assertEquals(
            collect(['testGroup', 'testGroup2']),
            $this->testUser->getGroupNames()->sort()->values()
        );
    }

    public function testItDoesNotDetachGroupsWhenSoftDeleting()
    {
        $user = SoftDeletingUser::create(['email' => 'test@example.com']);
        $user->assignGroup('testGroup');
        $user->delete();

        $user = SoftDeletingUser::withTrashed()->find($user->id);

        $this->assertTrue($user->hasGroup('testGroup'));
    }
}