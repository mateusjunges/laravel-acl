<?php
namespace MateusJunges\ACL\Http\ViewComposers;


use Illuminate\View\View;
use MateusJunges\ACL\LaravelACL;

class ACLComposer
{
    /**
     * @var LaravelACL
     */
    private $acl;

    /**
     * ACLComposer constructor.
     * @param LaravelACL $acl
     */
    public function __construct(LaravelACL $acl)
    {
        $this->acl = $acl;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('acl', $this->acl);
    }
}