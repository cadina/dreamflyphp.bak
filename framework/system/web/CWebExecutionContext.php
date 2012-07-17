<?php

class CWebExecutionContext extends CExecutionContext
{
    public $request;
    public $action;
    public $params = [];
    public $view;
    public $data;
}