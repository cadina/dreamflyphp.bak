<?php

interface IRoute
{
    public function match($request, &$action, &$params);
    public function generate($params);
}