<?php

namespace MateusJunges\ACL\Http\Menu\Filters;

use Illuminate\Contracts\Routing\UrlGenerator;
use MateusJunges\ACL\Http\Menu\Builder;

class HrefFilter
{
    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * HrefFilter constructor.
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param $item
     * @param Builder $builder
     * @return mixed
     */
    public function transform($item, Builder $builder)
    {
        if (!isset($item['header']))
            $item['href'] = $this->makeHref($item);
        return $item;
    }

    /**
     * Build the url of a menu item
     * @param $item
     * @return string
     */
    protected function makeHref($item)
    {
        if (isset($item['url']))
            return $this->urlGenerator->to($item['url']);
        if (isset($item['route']))
            return $this->urlGenerator->route($item['route']);

        return '#';
    }
}