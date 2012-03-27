<?php

class CComponent
{
    protected $app;

    protected function initialze()
    {
    }


    public function __construct($app)
    {
        $this->app = $app;
        $this->initialze();
    }

}