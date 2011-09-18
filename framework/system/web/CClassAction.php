<?php

/**
 * CClassAction
 * 
 * @author cadina
 */
class CClassAction extends CAction
{

    protected $beforeExcutionEvent = new CEvent;

    protected $afterExcutionEvent = new CEvent;

    public function execute($context)
    {
        $this->beforeExecutionEvent->raise();
        $result = $this->run();
        $this->afterExcutionEvent->raise();
        return $result;
    }

    protected function run()
    {
    }

}
