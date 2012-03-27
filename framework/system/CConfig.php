<?php

/**
 * CConfig
 * 
 * @author cadina
 */
final class CConfig implements IteratorAggregate, ArrayAccess
{
    private $_config = [];

    public function __construct(&$config = [])
    {
        $this->_config =& $config;
    }

    public function __get($name)
    {
        return $this->_config[$name];
    }

    public function __set($name, $value)
    {
        $this->_config[$name] = $value;
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->_config);
    }
    
    public function offsetExists($offset)
    {
        return isset($this->_config[$offset]);
    }
    
    public function offsetGet($offset)
    {   
        return $this->_config[$offset];
    }
    
    public function offsetSet($offset, $value)
    {
        $this->_config[$offset] = $value;
    }
    
    public function offsetUnset($offset)
    {
        unset($this->_config[$offset]);
    }
    
}
