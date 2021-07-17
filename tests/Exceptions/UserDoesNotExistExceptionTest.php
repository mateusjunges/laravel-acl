<?php

namespace Junges\ACL\Tests\Exceptions;

use Junges\ACL\Exceptions\UserDoesNotExistException;
use Junges\ACL\Tests\TestCase;

class UserDoesNotExistExceptionTest extends TestCase
{
    public function test_exception_message_via_name()
    {
        $this->expectException(UserDoesNotExistException::class);

        $this->expectExceptionMessage('There is no user with this name: Test not found user');

        $this->testUserGroup->assignUser('Test not found user');
    }

    public function test_exception_message_via_id()
    {
        $this->expectException(UserDoesNotExistException::class);

        $this->expectExceptionMessage('There is no user with this id: 123456789');

        $this->testUserGroup->assignUser(123456789);
    }
}
