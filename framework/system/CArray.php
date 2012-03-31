<?php

class CArray implements IteratorAggregate, ArrayAccess
{
    protected $array = [];

    public function __construct(&$array = [])
    {
        $this->array =& $array;
    }

    public function __get($name)
    {
        return $this->array[$name];
    }

    public function __set($name, $value)
    {
        $this->array[$name] = $value;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->array);
    }

    public function offsetExists($offset)
    {
        if (is_array($offset)) {
            foreach ($offset as $suboffset) {
                if (!isset($this->array[$suboffset])) {
                    return false;
                }
            }
            return true;
        }
        return isset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        if (is_array($offset)) {
            $result = [];
            foreach ($offset as $key => $suboffset) {
                $result[$suboffset] = $this->array[$suboffset];
            }
            return $result;
        }
        return $this->array[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_array($offset)) {
            foreach ($offset as $key => $suboffset) {
                $this->array[$suboffset] = $value[$key];
            }
        }
        $this->array[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (is_array($offset)) {
            foreach ($offset as $suboffset) {
                unset($this->array[$offset]);
            }
        }
        unset($this->array[$offset]);
    }

    public function getArray()
    {
        return $this->array;
    }
}