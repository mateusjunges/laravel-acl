<?php

namespace MateusJunges\ACL\Http\Menu\Filters;

use MateusJunges\ACL\Http\Menu\Builder;

class ClassesFilter
{
    /**
     * @param $item
     * @param Builder $builder
     * @return mixed
     */
    public function transform($item, Builder $builder)
    {
        if (!isset($item['header'])){
            $item['classes'] = $this->makeClasses($item);
            $item['class'] = implode(' ', $item['classes']);
        }
        return $item;
    }

    /**
     * @param $item
     * @return array|string
     */
    public function makeClasses($item)
    {
        $classes = [];

        if ($item['active'])
            $classes[] = 'active';

        if (isset($item['submenu']))
            $classes[] = 'treeview';

        return $classes;
    }
}