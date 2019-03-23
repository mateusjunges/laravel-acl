<?php

namespace MateusJunges\ACL\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Unauthorized extends HttpException
{
    /**
     * Exception used when user does not have the required groups to access some route
     * @return Unauthorized
     */
    public static function forGroups() : self
    {
        $message = "Este usuário não possui os grupos necessários para acessar esta rota.";
        return  new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }

    /**
     * Exception used when user does not have the required permissions to access some route
     * @return Unauthorized
     */
    public static function forPermissions() : self
    {
        $message = "Este usuário não possui as permissões necessárias para acessar esta rota";
        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }

    /**
     * User are not logged in
     * @return Unauthorized
     */
    public static function notLoggedIn() : self
    {
        $message = "Você não está logado no sistema.";
        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }

    /**
     * Used to return the exception when the user doesn't have any of the required permissions
     * @return Unauthorized
     */
    public static function forGroupsOrPermissions() : self
    {
        $message = "Você não possui nenhuma das permissões necessárias para acessar esta rota";
        return new static(Response::HTTP_FORBIDDEN, $message, null, []);
    }

}