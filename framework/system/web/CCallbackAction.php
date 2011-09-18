<?php

class CCallbackAction extends CAction
{
    protected $callback;

    public function __construct($controller, $name, $callback)
    {
        parent::__construct($controller, $name);
        $this->callback = $callback;
    }

    public function execute($context)
    {
        call_user_func_array($this->callback, $context->params);
    }
}