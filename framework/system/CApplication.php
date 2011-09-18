<?php

/**
 * CApplication
 * Base class for application classes.
 * 
 * @author cadina
 */
abstract class CApplication extends CComponent implements IApplication
{

	private $_modules = array();
	
	private $_moduleInstances  = array();
	
	public function __construct()
	{
	}
	
	public function __get($name)
	{
	    return $this->getModule($name);
	}
	
    public function getModule($name)
    {
        if (isset($this->_moduleInstances[$name])) return $this->_moduleInstances[$name];
        else if (isset($this->_modules[$name]))
        {
            $path = $this->_modules[$name];
            if ($file = map($path, $className)) require_once $file;
            $module = $this->_moduleInstances[$name] = call_user_func($className . '::getInstance');
            return $module;
        }
        else syserr();
    }
    
    public function setModule($name, $module)
    {
        if (is_string($module)) $this->_modules[$name] = $module;
        else if ($module instanceof CModule) $this->_moduleInstances[$name] = $module;
        else syserr();
    }

    protected function configs()
    {
        return parent::configs() + array(
            'modules'   => array($this, 'configModules'),
        );
    }
    
    protected function configModules($modules)
    {
        foreach ($modules as $name => $module)
            $this->_modules[$name] = $module;
    }

}
