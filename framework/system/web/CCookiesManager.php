<?php

class CCookiesManager extends CModule implements IteratorAggregate, IArrayAccess
{
    public function exists($name)
    {
        return isset($_COOKIES[$name]);
    }
    
    public function get($name)
    {
        return $_COOKIES[$name];
    }
    
    public function set()
    {
        call_user_func_array('setcookie', func_get_args());
    }
    
    public function delete($name)
    {
        unset($_COOKIES[$name]);
    }
    
    public function getIterator()
    {
        return new ArrayIterator($_COOKIES);
    }
    
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }
    
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
    
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }
    
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }
    
    protected function initialize()
    {
        parent::initialize();
    }
}
