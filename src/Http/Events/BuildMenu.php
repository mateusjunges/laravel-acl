<?php

namespace MateusJunges\ACL\Http\Events;

use MateusJunges\ACL\Http\Menu\Builder;

class BuildMenu
{
    public $menu;

    public function __construct(Builder $menu)
    {
        $this->menu = $menu;
    }
}