<?php

namespace MateusJunges\ACL\Http\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use MateusJunges\ACL\Http\Models\User;
use MateusJunges\ACL\Http\Menu\Builder;

class GateFilter
{
    /**
     * @var Gate
     */
    protected $gate;

    /**
     * GateFilter constructor.
     * @param Gate $gate
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * @param $item
     * @param Builder $builder
     * @return bool
     */
    public function transform($item, Builder $builder)
    {
        if (!$this->isVisible($item))
            return false;
        return $item;
    }


    /**
     * Determine if a menu item is visible, depending of the 'can' parameter
     * @param $item
     * @return bool
     */
    public function isVisible($item)
    {
        if (!isset($item['can']))
            return true;
        if (isset($item['model']))
            return $this->gate->allows($item['can'], $item['model']);
        return $this->gate->allows($item['can']);
    }
}