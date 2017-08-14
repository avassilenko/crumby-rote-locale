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