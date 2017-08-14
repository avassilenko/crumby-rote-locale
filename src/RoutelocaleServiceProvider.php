<?php
namespace Crumby\Routelocale;

use Illuminate\Support\ServiceProvider;

class RoutelocaleServiceProvider extends ServiceProvider
{
    const ROUTELOCALE_VAR_NAME = 'routelocale';
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(self::ROUTELOCALE_VAR_NAME, function ($app) {
            $routelocale = new Routelocale();

            \View::share(self::ROUTELOCALE_VAR_NAME, $routelocale);
            return $routelocale;
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/routelocale.php' => config_path('routelocale.php')
            ], 'config');
        }
        
        $this->app->alias(self::ROUTELOCALE_VAR_NAME, 'Crumby\Routelocale\Routelocale');
        \Routelocale::loadConfig();
    }
}
