<?php

class CLoader
{
    const AUTOLOAD_SYMBOL = '*';

    private static $_namespaces = [];

    private static $_autoloads = [];


    private static function _check($name)
    {
        return true;
    }
    
    private static function _parse($name, &$namespace, &$classname)
    {
        if ($pos = strrpos($name, NS)) {
            $namespace = substr($name, 0, $pos);
            $classname = substr($name, $pos + 1);
        }
        else {
            $namespace = '';
            $classname = $name;
        }
    }

    private static function _map($namespace)
    {
        foreach (self::$_namespaces as $ns => $dir) {
            $len = strlen($ns);
            if ($namespace == $ns
            or substr($namespace, 0, strlen($ns) + 1) == $ns . NS) {
                $subdir = implode(DS, explode(NS, substr($namespace, $len)));
                return $dir . DS . $subdir;
            }
        }
        return false;
    }

    private static function _search($namespace, $classname)
    {
        if (!empty($namespace)) {
            if (!($dir = self::_map($namespace))) {
                return false;
            }
        }
        else {
            $dir = '';
        }
        $file = $dir . DS . $classname . SCRIPT_EXT;
        return file_exists($file) ? $file : false;
    }


    public static function load($name, $critical = true)
    {
        if (!self::_check($name)) {
            throw new CException();
        }
        self::_parse($name, $namespace, $classname);
        if ($classname == self::AUTOLOAD_SYMBOL) {
            $directory = self::_map($namespace);
            array_unshift(self::$_autoloads, $directory);
        }
        else {
            $file = self::_search($namespace, $classname);
            if (!$file) {
                if ($critical) {
                    throw new CException();
                }
                else {
                    return false;
                }
            }
            return require($file);
        }
    }

    public static function create($name, $args = [])
    {
        if (!self::_check($name)) {
            throw new CException();
        }
        self::_parse($name, $namespace, $classname);
        $file = self::_search($namespace, $classname);
        if (!$file) {
            throw new CException();
        }
        require_once $file;
        return (new ReflectionClass($classname))->newInstanceArgs($args);
    }

    public static function loadAll($names)
    {
        return array_walk($names, function(&$name, $key) {
            $name = self::load($name);
        });
    }

    public static function registerNamespace($namespace, $directory)
    {
        self::$_namespaces[$namespace] = $directory;
    }

    public static function autoloadCallback($classname)
    {
        foreach (self::$_autoloads as $directory) {
            $file = $directory . DS . $classname . SCRIPT_EXT;
            if (file_exists($file)) {
                require($file);
            }
        }
    }

    public static function enableAutoload()
    {
        spl_autoload_register('CLoader::autoloadCallback');
    }

    public static function disableAutoload()
    {
        spl_autoload_unregister('CLoader::autoloadCallback');
    }

}
