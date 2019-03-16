<?php

namespace MateusJunges\ACL;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use MateusJunges\ACL\Http\Events\BuildMenu;
use MateusJunges\ACL\Http\Menu\Builder;

class LaravelACL
{
    /**
     * @var array
     */
    protected $menu;
    /**
     * @var array
     */

    protected $filters;
    /**
     * @var Dispatcher
     */

    protected $events;
    /**
     * @var Container
     */
    protected $container;

    /**
     * MateusJungesACL constructor.
     * @param array $filters
     * @param Dispatcher $events
     * @param Container $container
     */
    public function __construct( array $filters, Dispatcher $events, Container $container)
    {
        $this->filters = $filters;
        $this->events = $events;
        $this->container = $container;
    }

    /**
     * Return the menu
     * @return array
     */
    public function menu()
    {
        if (!$this->menu)
            $this->menu = $this->buildMenu();
        return $this->menu;
    }

    /**
     * Build the menu options
     * @return array
     */
    protected function buildMenu()
    {
        $builder = new Builder($this->buildFilters());

        if (method_exists($this->events, 'dispatch'))
            $this->events->dispatch(new BuildMenu($builder));
        else
            $this->events->fire(new BuildMenu($builder));

        return $builder->menu;
    }

    /**
     * @return array
     */
    protected function buildFilters()
    {
        return array_map([$this->container, 'make'], $this->filters);
    }
}