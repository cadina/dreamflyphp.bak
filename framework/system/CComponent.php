<?php

class CComponent
{
    protected $app;

    protected function initialize()
    {
    }


    public function __construct($app)
    {
        $this->app = $app;
        $this->initialize();
    }

}