<?php

namespace MateusJunges\ACL\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use MateusJunges\ACL\Http\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        try{
            dd(User::all());
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
        }
    }
}
