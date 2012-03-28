<?php

/**
 * CApplication
 * Base class for application classes.
 * 
 * @author cadina
 */
abstract class CApplication
{
    use TConfigurable;

    private $_directory;
    private $_namespace;


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
            'preloads' => function($preloads) {
                CLoader::loadAll($preloads);
            },
        ];
    }


    public function __construct($namespace, $directory)
    {
        CLoader::registerNamespace($namespace, $directory);
        $this->_directory = $directory;
        $this->_namespace = $namespace;
        $this->initialize();
    }

    public function loadConfig($name)
    {
        $configArray = CLoader::load($this->getNamespace().NS.'configs'.NS.$name);
        return new CConfig($configArray);
    }
    
}
