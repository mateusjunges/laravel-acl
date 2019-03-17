<?php

namespace MateusJunges\ACL;


use Illuminate\Auth\Access\Gate;

class ACL
{
    protected $gate;

    protected $permissionClass;

    protected $userHasGroupClass; //User has group

    protected $groupClass;

    protected $userClass;

    protected $userHasPermissionClass;

    protected $userHasDeniedPermissionClass;


    /**
     * ACL constructor.
     * @param Gate $gate
     */
    public function __construct(Gate $gate)
    {
        $this->permissionClass = config('acl.models.permission');
        $this->groupClass = config('acl.models.group');
        $this->gate = $gate;
        $this->userHasGroupClass = config('acl.models.user_has_group');
        $this->userHasDeniedPermissionClass = config('acl.models.user_has_denied_permission');
        $this->userHasPermissionClass = config('acl.models.user_has_permission');
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getPermissionClass()
    {
        return app($this->permissionClass);
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getUserClass()
    {
        return app($this->userClass);
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getUserHasGroupClass()
    {
        return app($this->getUserHasGroupClass());
    }

}