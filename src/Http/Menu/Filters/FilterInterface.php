<?php
namespace MateusJunges\ACL\Http\Menu\Filters;

use MateusJunges\ACL\Http\Menu\Builder;

interface FilterInterface
{
    public function transform($item, Builder $builder);
}