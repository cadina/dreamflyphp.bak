<?php

class CWebApplication extends CApplication
{
    protected $siteUrl;

    protected $request;
    protected $router;


    protected function initialize()
    {
        parent::initialize();

        $this->request = new CWebRequest($this);
        $this->router = new CRouter($this);
    }

	protected function configs()
	{
	    return parent::configs() + [
            'siteUrl',
	    ];
	}


	final public function run()
	{
        $request = $this->request;
        $router = $this->router;

        $this->router->resolve($request, $actionName, $params);
        $context = new CWebExecutionContext();
        $context->actionName = $actionName;
        $context->params = $params;
        $this->loadAction($actionName);
        $actionClassName = $actionName.'Action';
        $action = new $actionClassName();
        $action->execute($context);
	}

    public function loadAction($name)
    {
        CLoader::load($this->getNamespace().NS.'actions'.NS.$name);
        $actionClassName = $name.'Action';
        $action = new $actionClassName;
        return $action;
    }
	
}
