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
                    $this->routes[$name] = $route;
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

