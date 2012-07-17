<?php

class CViewResult extends CActionResult
{
    protected $view;

    protected $data;

    public function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function execute($context)
    {
        $context->view = $this->view;
        $context->data = $this->data;
        $this->view->execute($context);
    }
}