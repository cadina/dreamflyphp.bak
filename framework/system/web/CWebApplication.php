<?php

class CWebApplication extends CApplication
{

	public $controllersDir = 'controller';

    protected $_siteUrl;


	final public function run()
	{
		$this->_initializeModules();
        $this->configure($this->loadConfig('application'));
		$this->initialize();
		$context = $this->poccessRequest();
		$this->executeController($context);
	}
	
	protected function poccessRequest()
	{
        $request = $this->getModule('request');
        $this->router->resolve($request, $controller, $action, $params);
        $context = new CWebExecutionContext();
        $context->controllerName = $controller;
        $context->actionName = $action;
        $context->params = $params;
        return $context;
	}
	
	protected function executeController($context)
	{
        $controllerName = $context->controllerName;
        need($this->namespace.NS.$this->controllersDir.NS.$controllerName);
        $controllerClassName = $controllerName . 'Controller';
        $controllerObject = new $controllerClassName();
        $controllerObject->execute($context);
	}
	
	protected function configs()
	{
	    return parent::configs() + array(
			'controllersDir',
	        'siteUrl' => '_siteUrl',
	    );
	}
	
	private function _initializeModules()
	{
	    $this->setModule('request',  'system.web.CWebRequest');
	    $this->setModule('response', 'system.web.CWebResponse');
	    $this->setModule('cookies', 'system.web.CCookiesManager');
        $this->setModule('router', 'system.web.CRouter');
	}

}
