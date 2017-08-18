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
























1. Route recognize route parameter {locale}.
File  app/Providers/RouteServiceProvider.php
<?php
......
use Crumby\Routelocale\RoutelocalePattern as RoutelocalePattern;
......
class RouteServiceProvider extends ServiceProvider
{
......
use RoutelocalePattern;
......
public function boot()
{
......
    $this->initRoutelocalePattern();
......        
    parent::boot();
}
2. Register service and facade. 
File: config/app.php


3. Register global middlewear.
File: app/Http/Kernel.php .  

 protected $middleware = [
        ..................
        Crumby\Routelocale\Middleware\RoutelocaleMiddleware::class
    ];

4. Add to file config/app.php
        'all_locales' => ['en', 'ru', 'es']
        
5. change file config/routelocale.php
        Set to true if whant to use locale prefix in route [0] segment
        'default_prefix' => false