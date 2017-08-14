<?php
namespace Crumby\Routelocale;

trait RoutelocalePattern  
{
    public function initRoutelocalePattern() {
        $allLocales = \Routelocale::getAllRoutelocales();
        if (!empty($allLocales)) {
            \Route::pattern('locale', implode("|", $allLocales) );
        }
    }   
}
