<?php

class CWebApplication extends CApplication
{
    protected $defaultAction = 'index';

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
	    return [
            'defaultAction',
	    ] + parent::configs();
	}


    public function run()
    {
        return $this->execute();
    }
    
	public function execute($context = null)
	{
        $request = $this->request;
        $router = $this->router;

        if (!$this->router->resolve($request, $actionName, $params)) {
            throw new CException();
        }
        if (!isset($context)) {
            $context = new CWebExecutionContext();
        }
        $context->request = $request;
        $context->params = $params;
        $context->application = $this;
        
        $action = $this->loadAction($actionName);
        $result = $action->execute($context);
        if (!empty($result)) {
            $result->execute($context);
        }
	}

    public function loadAction($name)
    {
        $parts = explode(NS, $name);
        $actionName = array_pop($parts);
        if (empty($actionName)) {
            $actionName = $this->defaultAction;
        }
        array_push($parts, $actionName);
        $name = implode(NS, $parts);
        CLoader::load($this->getNamespace().NS.'actions'.NS.$name);
        $actionClassName = str_replace('-', '', substr(strrchr($name, NS) ?: NS.$name, 1)).'Action';
        $action = new $actionClassName($this);
        return $action;
    }

    public function loadView($name)
    {
        $view = new CView($this->getNamespace().NS.'views'.NS.$name);
        return $view;
    }

}
