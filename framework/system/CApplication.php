<?php

/**
 * CApplication
 * Base class for application classes.
 * 
 * @author cadina
 */
abstract class CApplication implements IExecutable
{
    use TConfigurable;

    private $_directory;
    private $_namespace;
    private $_modules = [];


    protected function initialize()
    {
        $this->configure($this->loadConfig('application'));
    }

    protected function getDirectory()
    {
        return $this->_directory;
    }

    protected function getNamespace()
    {
        return $this->_namespace;
    }
	
    protected function configs()
    {
        return [
            'autoloads' => function($preloads) {
                CLoader::loadAll($preloads);
            },
            'modules' => function($modules) {
                $modules = array_merge($this->modules(), $modules);
                foreach ($modules as $name => $module) {
                    if (is_int($name)) {
                        $name = is_string($module) ? $module : $module['name'];
                    }
                    $this->_modules[$name] = $module;
                }
            },
        ];
    }

    protected function modules()
    {
        return [];
    }


    public function __construct($namespace, $directory)
    {
        CLoader::registerNamespace($namespace, $directory);
        $this->_directory = $directory;
        $this->_namespace = $namespace;
        $this->initialize();
    }

    public function loadConfig($name, $defaults = [])
    {
        $configName = $this->getNamespace() . NS . 'configs' . NS . $name;
        $configArray = CLoader::load($configName, false) ?: $defaults;
        return new CConfig($configArray);
    }

    public function getModule($name)
    {
        if (!isset($this->_modules[$name])) {
            throw new CException();
        }
        $module =& $this->_modules[$name];
        if (is_string($module)) {
            $moduleClassname = $module;
            $module = CLoader::create($moduleClassname, [$this]);
        }
        else if (is_array($module)) {
            $moduleClassname = $module['class'];
            $moduleInstance = CLoader::create($moduleClassname, [$this] + ($module['args'] ?: []));
            if (isset($module['vars'])) {
                foreach ($module['vars'] as $key => $value) {
                    $moduleInstance->$key = $value;
                }
            }
            $module = $moduleInstance;
        }
        return $module;
    }
    
}
