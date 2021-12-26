<?php

namespace Junges\ACL\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Junges\ACL\Exceptions\UnauthorizedException;

class MiddlewareTestCase extends TestCase
{
    protected function runMiddleware($middleware, $groupName, $guard = null)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, $groupName, $guard)->status();
        } catch (UnauthorizedException $e) {
            return $e->getStatusCode();
        }
    }
}