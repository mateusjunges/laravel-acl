<?php

namespace MateusJunges\ACL\Http\Menu;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActiveChecker
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var UrlGenerator
     */
    private $url;

    /**
     * ActiveChecker constructor.
     * @param Request $request
     * @param UrlGenerator $url
     */
    public function __construct(Request $request, UrlGenerator $url)
    {
        $this->request = $request;
        $this->url = $url;
    }

    /**
     * @param $item
     * @return bool
     */
    public function isActive($item)
    {
        if (isset($item['active']))
            return $this->isExplicitActive($item['active']);

        if (isset($item['submenu']))
            return $this->containsActive($item['submenu']);

        if (isset($item['href']))
            return $this->checkExactOrSub($item['href']);

        if (isset($item['url']))
            return $this->checkExactOrSub($item['url']);
    }



    /**
     * @param $url
     * @return bool
     */
    protected function checkExactOrSub($url)
    {
        return $this->checkExact($url) || $this->checkSub($url);
    }


    protected function checkExact($url)
    {
        return $this->checkPattern($url);
    }
    /**
     * @param $url
     * @return bool
     */
    protected function checkSub($url)
    {
        return $this->checkPattern($url.'/*') || $this->checkPattern($url.'?*');
    }

    /**
     * @param $pattern
     * @return bool
     */
    protected function checkPattern($pattern)
    {
        $fullUrlPattern = $this->url->to($pattern);
        $fullUrl = $this->request->fullUrl();

        return Str::is($fullUrlPattern, $fullUrl);
    }

    /**
     * @param $items
     * @return bool
     */
    protected function containsActive($items)
    {
        foreach ($items as $item) {
            if ($this->isActive($item))
                return true;
        }
        return false;
    }

    /**
     * @param $active
     * @return bool
     */
    public function isExplicitActive($active)
    {
        foreach ($active as $url) {
            if ($this->checkExact($url))
                return true;
        }
        return false;
    }
}