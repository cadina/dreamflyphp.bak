<?php

abstract class CAction extends CBase implements IExecutable
{
    protected $controller;

    protected $name;

    public function __construct($controller, $name)
    {
        $this->controller = $controller;
        $this->name = $name;
    }
}
