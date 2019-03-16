<?php

namespace MateusJunges\ACL\Http\Menu\Filters;

use MateusJunges\ACL\Http\Menu\Builder;

class SubmenuFilter
{

    /**
     * @param $item
     * @param Builder $builder
     * @return mixed
     */
    public function transform($item, Builder $builder)
    {
        if (isset($item['submenu'])){
            $item['submenu'] = $builder->transformItens($item['submenu']);
            $item['submenu_open'] = $item['active'];
            $item['submenu_classes'] = $this->makeSubMenuClasses();
            $item['submenu_class'] = implode(' ', $item['submenu_classes']);
        }
        return $item;
    }

    /**
     * @return array
     */
    protected function makeSubMenuClasses()
    {
        $classes = ['treeview-menu'];
        return $classes;
    }
}