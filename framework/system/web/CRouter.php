<?php

class CRouter extends CComponent
{
    use TConfigurable;

    protected $routes = [];

    
    protected function configs()
    {
        return [
            'routes' => function($routes) {
                foreach ($routes as $name => $route) {
                    if (is_object($route)) {
                        $this->routes[$name] = $route;
                    }
                    else if (is_array($route)) {
                        $class = array_shift($route);
                        $route = CLoader::create($class, $route);
                        $this->routes[$name] = $route;
                    }
                    else {
                        throw new CException;
                    }
                }
                //ensure all the routes implements IRoute
                foreach ($routes as $route) {
                    if (!($route instanceof IRoute)) {
                        throw new CException;
                    }
                }
            },
        ];
    }

    
    public function initialize()
    {
        $this->configure($this->app->loadConfig('route'));
    }
    
    public function resolve($request, &$action, &$params)
    {
        foreach ($this->routes as $name => $route) {
            if ($name = $route->match($request, $action, $params)) {
                return $name;
            }
        }
        return false;
    }
    
    public function generate($name, $params)
    {
        return $this->routes[$name]->generate($params);
    }

}

