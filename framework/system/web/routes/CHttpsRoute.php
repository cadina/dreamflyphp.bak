<?php

class CHttpsRoute extends CWrapRoute
{
    public function __construct($routes)
    {
        parant::__construct($routes);
    }

    public function matchWrap($request, &$action, &$params)
    {
        return $request->https;
    }

    public function generate($params)
    {
    }
}