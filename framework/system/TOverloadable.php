<?php

trait TOverloadable
{

    protected function call($overload, $arguments)
    {
        $argscount = count($arguments);
        
        //initializing
        if (is_string($overload) || is_int($overload))
        {
            if (!isset(self::$overloadsRaw)) self::$overloadsRaw = static::overloads();
            if (!isset(self::$overloads[$overload])) 
            {
                self::$overloads[$overload] =& self::$overloadsRaw[$overload];
                $parse = true;
            }
            else $parse = false;
            $overload =& self::$overloads[$overload];
        }
        elseif (is_array($overload))
        {
            $parse = true;
        }
        else throw new Exception;
        
        //parsing
        if ($parse)
        {
            foreach ($overload as $method => $patternRaw)
            {
                $typenames = explode(',', $patternRaw);
                $pattern = new SplFixedArray(count($typenames));
                foreach ($typenames as $index => $typename)
                {
                    $type = 0x0000;
                    if (preg_match('/^(?P<type>\w+)\?$/', $typename, $matches))
                    {
                        $typename = $matches['type'];
                        $type |= ARG_TYPE_NULL;
                    }
                    
                    switch ($typename)
                    {
                        case 'bool':
                        case 'boolean': $type |= ARG_TYPE_BOOLEAN; break;
                        case 'int':
                        case 'integer':
                        case 'long': $type |= ARG_TYPE_INTEGER; break;
                        case 'float':
                        case 'double':
                        case 'real': $type |= ARG_TYPE_REAL; break;
                        case 'string': $type |= ARG_TYPE_STRING; break;
                        case 'array': $type |= ARG_TYPE_ARRAY; break;
                        case 'object': $type |= ARG_TYPE_OBJECT; break;
                        case 'resource': $type |= ARG_TYPE_RESOURCE; break;
                        case 'callback': $type |= ARG_TYPE_CALLBACK; break;
                        case 'numeric': $type |= ARG_TYPE_NUMERIC; break;
                        case 'scalar': $type |= ARG_TYPE_SCALAR; break;
                        case 'mixed': $type |= ARG_TYPE_MIXED; break;
                        default: $type |= $type; $cls = $typename; break;
                    }
                    
                    $pattern[0][$index] = $type;
                    if (isset($cls)) $pattern[1][$index] = $cls;
                }
                $overload[$method] = $pattern;
            }
        }
        
        //matching
        $match = false;
        foreach ($overload as $method => $pattern)
        {
            if (count($pattern) === $argscount)
            {
                foreach ($pattern as $index => $types)
                {
                    $type = $pattern[0][$index];
                    $cls = $pattern[1][$index];
                    $argument =& $arguments[$index];
                    
                    if (($types[$index] | $type) == $type);
                    else if (($type & ARG_TYPE_CALLBACK) && is_callable($argument));
                    else continue 2;
                    
                    if ($type != ARG_TYPE_NULL && $cls != null && !is_a($argument, $cls)) continue 2;
                }
                $match = $method;
                break;
            }
        }
        
        //calling
        if ($match) return call_user_func_array(array($this, $match), $arguments);
        else throw new CException;
    }
    
    static private $overloadsRaw;
    
    static private $overloads = array();
    
    static protected function overloads()
    {
        return array();
    }
}


