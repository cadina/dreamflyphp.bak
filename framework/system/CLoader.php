<?php

class CLoader
{
    const AUTOLOAD_SYMBOL = '*';

    private static $_namespaces = [];

    private static $_autoloads = [];


    public static function load($name)
    {
        $parts = explode(NS, $name);
        $classname = array_pop($parts);
        $prefix = array_shift($parts) or '';
        if ($classname == self::AUTOLOAD_SYMBOL) {
            if (!empty($prefix)) {
                $directory = self::$_namespaces[$prefix].DS.implode(DS, $parts);
                array_unshift(self::$_autoloads, $directory);
            }
        }
        else {
            foreach (self::$_namespaces as $namespace => $directory) {
                if ($prefix == $namespace) {
                    $file = $directory.DS.implode(DS, $parts).DS.$classname.SCRIPT_EXT;
                    return require($file);
                }
            }
        }
    }

    public static function loadAll($names)
    {
        return array_walk($names, function($name) {
            return self::load($name);
        });
    }

    public static function registerNamespace($namespace, $directory)
    {
        self::$_namespaces[$namespace] = $directory;
    }

    public static function autoloadCallback($classname)
    {
        foreach (self::$_autoloads as $directory) {
            $file = $directory.DS.$classname.SCRIPT_EXT;
            if (file_exists($file)) {
                require($file);
            }
        }
    }

}

function load($name)
{
    if (is_array($name)) {
        return CLoader::loadAll($name);
    }
    return CLoader::load($name);
}

spl_autoload_register('CLoader::autoloadCallback');