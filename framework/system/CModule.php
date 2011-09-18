<?php

/**
 * CMudule
 * Base class of singleton pattern
 * 
 * @author cadina
 */
class CModule extends CComponent implements ISingleton
{
    private static $instances = array();
    
    public static function getInstance()
    {
        $className = get_called_class();
        if (!isset(self::$instances[$className]))
            self::$instances[$className] = new static();
        return self::$instances[$className];
    }

    private function __construct()
    {
        $this->initialize();
    }
}
