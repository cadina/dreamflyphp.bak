<?php

abstract class CAction extends CComponent implements IExecutable
{
    protected $request;

    public function execute($context)
    {
        $this->request = $context->request;
        $method = [$this, $context->request->method];
        call_user_func_array($method, $context->params);
    }
}
