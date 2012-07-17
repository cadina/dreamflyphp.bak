<?php

class CView implements IExecutable
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function execute($context)
    {
        CLoader::load($this->name, true, $context->data);
    }
}