<?php

abstract class CAction extends CComponent implements IExecutable
{
    protected $request;

    public function execute($context)
    {
        $this->request = $context->request;
        $method = [$this, $context->request->method];
        return call_user_func_array($method, $context->params);
    }

    protected function view($name, $data = [])
    {
        return new CViewResult($this->app->loadView($name), $data);
    }

    protected function json($data, $options = 0)
    {
        return new CJsonResult($data, $options);
    }

    protected function redirect($location)
    {
        return new CHttpResult(null, "Location: {$location}");
    }

    protected function http($status = null, $header = null, $body = null)
    {
        return new CHttpResult($status, $header, $body);
    }
}
