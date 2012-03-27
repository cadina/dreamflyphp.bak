<?php

abstract class CAction implements IExecutable
{
    public function execute($context)
    {
        call_user_func_array([$this, 'run'], $context->params);
    }
}
