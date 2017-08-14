<?php
namespace Crumby\Routelocale;
use Crumby\RouteResolver\Contracts\ParamResolver as ParamResolver;

/**
 * Description of Routelocale
 *
 * @author Andrei Vassilenko <avassilenko2@gmail.com>
 */
class Routelocale {
    protected $allLocales;
    protected $defaultLocales;
    protected $usePrefixForDefault;
    
    public function __construct() {
        $this->loadConfig();
    }
    
    /**
     * Load Routelocale configuration. All avalable locales and default locale.
     */
    public function loadConfig() {
        $this->allLocales = config('app.all_locales');
        $this->defaultLocales = config('app.fallback_locale');
        $this->usePrefixForDefault = config('routelocale.default_prefix');
    }
    
    /**
     * Returns All avalable locales.
     * @return array All avalable locales
     */
    public function getAllRoutelocales() {
        return $this->allLocales;
    }
    
    /**
     * Returns All avalable locales.
     * @return string Default locale
     */
    public function getDefaultRoutelocale() {
        return $this->defaultLocales;
    }
    
    /**
     * Returns ff use locale prefix in [0] segment if current locale is default.
     * @return boolean
     */
    public function isUsePrefixForDefaultLocale() {
        return $this->usePrefixForDefault;
    }
    
    /**
     * Test if locale segment is set. 
     * Example: /en/contact-us returns true, if 'en' set in 'routelocale.all_locales' or fallback to 'en' in 'app.fallback_locale'. Otherwize returns false.
     * 
     * @param  array|null $segments. The route segments. If null - segments will be pulled from current request
     * @return boolean
     */
    public function isSetLocaleSegmentInRoute($segments = null) {
        // if segmets are not set - get from current request
        if (is_null($segments)) {
            $segments = \Request::segments();
        }
        if ($allLocales = \Routelocale::getAllRoutelocales()) {
            return in_array(array_shift($segments), $allLocales);
        }
        
        return \Routelocale::getDefaultRoutelocale() === array_shift($segments);
    }
    
    /**
     * Test if the route set to default locale.
     * @param  array|null $segments. The route segments. If null - segments will be pulled from current request
     * @return boolean
     */
    public function isDefaultLocaleInRoute($segments = null) {
       return $this->getDefaultRoutelocale() === $this->getLocaleFromRoute($segments);
    }
    
    
    /**
     * Returns the route locale. Fallback to default locale.
     * @param  array|null $segments. The route segments. If null - segments will be pulled from current request
     * @return string Locale
     */
    public function getLocaleFromRoute($segments = null) {
        // if segmets are not set - get from current request
        if (is_null($segments)) {
            $segments = \Request::segments();
        }
        $locale = $this->getDefaultRoutelocale();
        if ($allLocales = \Routelocale::getAllRoutelocales()) {
            // reduce array of segments to [0] and find by value
            if ($curLocaleIndex = array_search(array_shift($segments), $allLocales)) {
                $locale = $allLocales[$curLocaleIndex];
            }  
        }
        return $locale;
    }
    
    /**
     * Get all locale routes:
     * @param  array|null $segments. The route segments. If null - segments will be pulled from current request
     * @return array routes with locale in [0] segment, default locale - without locale in [0] segment
     */
    public function getAllLocalizedRoutes($segments = null, $leadSlash = true, $fullPath = false) {
        /*
         * routes with dynamic parameter(s)
         */
        if ($allRoutes = $this->getAllLocalazedDynamicRoutes($segments, $leadSlash, $fullPath)) {
            return $allRoutes;
        }
        
        /*
         * static routes
         */
        $allRoutes = [];
        // if segmets are not set - get from current request
        if (is_null($segments)) {
            $segments = \Request::segments();
        }
        elseif (is_string($segments)) {
            $segments = explode('/', $segments);
        }
        
        // if locale segment set - remove it from [0]
        if ($this->isSetLocaleSegmentInRoute($segments)) {
            array_shift($segments);
        }

        // loop each posiable locales and build localized rote
        foreach($this->getAllRoutelocales() as $locale) {
            $path = '';
            if (($this->getDefaultRoutelocale() !== $locale) || $this->isUsePrefixForDefaultLocale()) {
                // add locale to [0] segment
                $path = ($leadSlash ? '/' : '') . implode('/', array_merge([$locale], $segments));
            }
            else {
                // if it is default locale - DO NOT add it to [0] segment
                $path = ($leadSlash ? '/' : '') . implode('/', $segments);
            }
            $allRoutes[$locale] = $fullPath ? \URL::to($path) : $path;
        }
        return $allRoutes;
    }
    
    public function getAllLocalazedDynamicRoutes($segments = null, $leadSlash = true, $fullPath = false) {
        $allRoutes = [];
        if (($allLocales = $this->getAllRoutelocales()) && ($resolvers = \RouteResolver::getFromRequest())) {   
           
            // get route path pattern , like '/packages/{package}'
            $uriWithParam = \Route::getCurrentRoute()->uri();

            $allRoutes = \RouteResolver::resolveRouteCollection($uriWithParam, $resolvers);

            foreach ($allRoutes as $locale => $urlWithoutLocale) {
                // add locale to route url if required
                $allRoutes[$locale] =  $this->getLocalizedRoute($urlWithoutLocale['url'], $locale, $leadSlash, $fullPath);
            }   
        }
        return empty($allRoutes) ? false :  $allRoutes;
    }
    
    /**
     * Get localized route:
     * @param array|null $segments. The route segments. If null - segments will be pulled from current request
     * @param string Locale to add to the route. If null - pick current locale. If default locale - add nothing, just strip any locale from [0] segment.
     * @return string route with locale in [0] segment, default locale - without locale in [0] segment
     */
    public function getLocalizedRoute($segments = null, $locale = null, $leadSlash = true, $fullPath = false) {
        $path = '';
        // if segmets are not set - get from current request
        if (is_null($segments)) {
            $segments = \Request::segments();
        }
        elseif (is_string($segments)) {
            $segments = explode('/', $segments);
        }
        // if locale is not set - get current
        if (is_null($locale)) {
            $locale = \App::getLocale();
        }
    
        // if locale segment set - remove it from [0]
        if ($this->isSetLocaleSegmentInRoute($segments)) {
            array_shift($segments);
        }
      
        // add locale to route
        if (($this->getDefaultRoutelocale() !== $locale)  || $this->isUsePrefixForDefaultLocale()) {
            $path = ($leadSlash ? '/' : '') . implode('/', array_merge([$locale], $segments));
        }
        else {
            $path = ($leadSlash ? '/' : '') . implode('/', $segments);
        }

        return $fullPath ? \URL::to($path) : $path;
    }
    
    /**
     * Returns locale to set as prefix to the route.
     * Returns empty string it is default locale and if configuration set to "no prefix" for default locale
     * 
     * @param type $locale
     * @return string Prefix to localized route. Like 'en'. 
     */
    public function getRouteLocalePrefix($locale = null) {
        if (is_null($locale)) {
            $locale = $this->getLocaleFromRoute();
        }
        return $this->getDefaultRoutelocale() === $locale ? ($this->isUsePrefixForDefaultLocale() ? $locale : '') : $locale;
    }
    
}
