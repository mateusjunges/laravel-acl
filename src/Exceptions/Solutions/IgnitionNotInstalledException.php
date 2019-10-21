<?php

namespace Junges\ACL\Exceptions\Solutions;

class IgnitionNotInstalledException extends \Exception
{
    /**
     * Throws an exception that the Facade's ignition package has not been installed.
     *
     * This exception will also show the install command for composer
     */
    public function __construct()
    {
        parent::__construct(trans('acl::acl.ignition_not_installed'));
    }
}
