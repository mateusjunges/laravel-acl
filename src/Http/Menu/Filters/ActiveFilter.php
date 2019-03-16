<?php

namespace MateusJunges\ACL\Http\Menu\Filters;

use MateusJunges\ACL\Http\Menu\Builder;
use MateusJunges\ACL\Http\Menu\ActiveChecker;

class ActiveFilter
{
    /**
     * @var ActiveChecker
     */
    private $activeChecker;

    /**
     * ActiveFilter constructor.
     * @param ActiveChecker $activeChecker
     */
    public function __construct(ActiveChecker $activeChecker)
    {
        $this->activeChecker = $activeChecker;
    }

    /**
     * @param $item
     * @param Builder $builder
     * @return mixed
     */
    public function transform($item, Builder $builder)
    {
        if (!isset($item['header']))
            $item['active'] = $this->activeChecker->isActive($item);

        return $item;
    }
}