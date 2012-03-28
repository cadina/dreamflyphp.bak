<?php

class CHttpsRoute implements IRoute
{
    protected $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function match($request, &$action, &$params)
    {
        return $request->https and $this->route->match($request, $action, $params);
    }

    public function generate($params)
    {
        return $this->route->generate($params);
    }
}