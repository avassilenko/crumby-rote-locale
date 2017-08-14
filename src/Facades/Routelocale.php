<?php

namespace Crumby\Routelocale\Facades;

use Illuminate\Support\Facades\Facade;

class Routelocale extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'routelocale';
    }
}
