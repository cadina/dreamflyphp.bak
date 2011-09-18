<?php

class CWebApplication extends CApplication
{

    protected $_siteUrl;


    final public function __construct()
    {
        parent::__construct();
        $this->_initializeModules();
    }

	final public function run()
	{
        $this->configure(CConfig::load('application'));
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
        need(APPLICATION_NAMESPACE_CONTROLLERS.NS.$controllerName);
        $controllerClassName = $controllerName . 'Controller';
        $controllerObject = new $controllerClassName();
        $controllerObject->execute($context);
	}
	
	protected function configs()
	{
	    return parent::configs() + array(
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
