<?php

class CDefaultRoute implements IRoute
{

    public function __construct()
    {
    }

    public function match($request, &$action, &$params)
    {
        $parts = explode('/', $request->path);
        $parts = array_filter($parts, function($part) {return !empty($part);});
        $action = implode(NS, $parts);
        $params = $request->get->getArray();
        return true;
    }

    public function generate($params)
    {
        $action = $params['action'];
        return DS . implode(DS, explode(NS, $action));
    }
}