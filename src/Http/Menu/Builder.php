<?php

namespace MateusJunges\ACL\Http\Menu;

class Builder
{
    public $menu = [];

    /**
     * @var array
     */
    private $filters;

    /**
     * Builder constructor.
     * @param array $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Add every menu item to the menu array
     */
    public function add()
    {
        $items = $this->transformItens(func_get_args());
        foreach ($items as $item) {
            array_push($this->menu, $item);
        }
    }

    /**
     * @param $items
     * @return array
     */
    public function transformItens($items)
    {
        return array_filter(array_map([$this, 'applyFilters'], $items));
    }

    public function applyFilters($item)
    {
        if (is_string($item))
            return $item;

        foreach ($this->filters as $filter) {
            $item = $filter->transform($item, $this);
        }

        if (isset($item['header']))
            $item = $item['header'];

        return $item;
    }

}