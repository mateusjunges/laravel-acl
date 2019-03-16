<?php
namespace MateusJunges\ACL\Http\ViewComposers;


use Illuminate\View\View;
use MateusJunges\ACL\MateusJungesACL;

class ACLComposer
{
    /**
     * @var MateusJungesACL
     */
    private $acl;

    /**
     * ACLComposer constructor.
     * @param MateusJungesACL $acl
     */
    public function __construct(MateusJungesACL $acl)
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