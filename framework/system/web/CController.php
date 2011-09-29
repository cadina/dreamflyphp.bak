<?php

/**
 * CController
 * 
 * @author cadina
 */
abstract class CController extends CBase implements IExecutable
{

    public $application;
    
    final public function __construct()
    {
    }
    
    final public function execute($context)
    {
        $this->initialize();
        $action = $this->_createAction($context->actionName);
        $result = $this->_executeAction($action, $context);
        $this->_executeResult($result, $context);
    }   

    private function _createAction($actionName)
    {
        $actionClass = 'CCallbackAction';
        $action = new $actionClass($this, $actionName, array($this, $actionName . 'Action'));
        return $action;
    }
    
    private function _executeAction($action, $context)
    {
        $result = $action->execute($context);
        return $result;
    }
    
    private function _executeResult($result, $context)
    {
        //$result->execute($context);
    }

}
