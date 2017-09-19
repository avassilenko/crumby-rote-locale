Installation:
-------------
```
> composer require crumby/route-locale:"dev-master"
> php artisan vendor:publish --provider="Crumby\Routelocale\RoutelocaleServiceProvider" --tag=config
```

Register service and facade:
----------------------------
File: config/app.php

```
'providers' => [
    ......................
    'Crumby\Routelocale\RoutelocaleServiceProvider',
    ........................
 ];
 
 'aliases' => [ 
    ......................
    'Routelocale' => 'Crumby\Routelocale\Facades\Routelocale',
    ......................
 ];
```

Example of building all existing localized urls for current route: 
----------------------------------------------------------------------
```
$allLocalizedUrls = \Routelocale::getAllLocalizedRoutes(null, false, true);
```

will output url used in hreflang:
```
<link rel="canonical" href="http://dev.myblogtest.com/packages/breadcrumbs-for-laravel-54" />
<link rel="alternate" hreflang="en" href="http://dev.myblogtest.com/packages/breadcrumbs-for-laravel-54" />
<link rel="alternate" hreflang="ru" href="http://dev.myblogtest.com/ru/packages/khlebnyekroshki-dlya-laravel-54" />
```
