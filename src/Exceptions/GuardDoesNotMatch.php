<?php

namespace Junges\ACL\Exceptions;

use Exception;
use Illuminate\Support\Collection;

class GuardDoesNotMatch extends Exception
{
    public static function create(string $givenGuard, Collection $expectedGuards)
    {
        return new static("The given role or permission should use guard `{$expectedGuards->implode(', ')}` instead of `{$givenGuard}`.");
    }
}
