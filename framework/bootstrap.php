<?php

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!defined('NAMESPACE_SEPARATOR')) define('NAMESPACE_SEPARATOR', '.');

if (!defined('NS')) define('NS', NAMESPACE_SEPARATOR);

if (!defined('FRAMEWORK_DIRECTORY')) define('FRAMEWORK_DIRECTORY', __DIR__);

if (!defined('FRAMEWORK_LIBRARY_DIRECTORY')) define('FRAMEWORK_LIBRARY_DIRECTORY', FRAMEWORK_DIRECTORY . DS . 'system');

if (!defined('SCRIPT_EXTENSION')) define('SCRIPT_EXTENSION', 'php');


if (!defined('SYSTEM_NAMESPACE')) define('SYSTEM_NAMESPACE', 'system');

if (!defined('APPLICATION_NAMESPACE')) define('APPLICATION_NAMESPACE', 'application');
if (!defined('APPLICATION_NAMESPACE_CONTROLLERS')) define('APPLICATION_NAMESPACE_CONTROLLERS', APPLICATION_NAMESPACE.NS.'controllers');
if (!defined('APPLICATION_NAMESPACE_CONFIGS')) define('APPLICATION_NAMESPACE_CONFIGS', APPLICATION_NAMESPACE.NS.'configs');

if (!defined('DEFAULT_NAMESPACE')) define('DEFAULT_NAMESPACE', SYSTEM_NAMESPACE);


define('ARG_TYPE_NULL',         0x0001);
define('ARG_TYPE_BOOLEAN',      0x0002);
define('ARG_TYPE_INTEGER',      0x0004);
define('ARG_TYPE_REAL',         0x0008);
define('ARG_TYPE_STRING',       0x0010);
define('ARG_TYPE_ARRAY',        0x0020);
define('ARG_TYPE_OBJECT',       0x0040);
define('ARG_TYPE_RESOURCE',     0x0080);
define('ARG_TYPE_CALLBACK',     0x0100);
define('ARG_TYPE_NUMERIC',      ARG_TYPE_INTEGER | ARG_TYPE_REAL);
define('ARG_TYPE_SCALAR',       ARG_TYPE_BOOLEAN | ARG_TYPE_STRING | ARG_TYPE_INTEGER | ARG_TYPE_REAL);
define('ARG_TYPE_MIXED',        ARG_TYPE_BOOLEAN | ARG_TYPE_INTEGER | ARG_TYPE_REAL | ARG_TYPE_STRING | ARG_TYPE_ARRAY | ARG_TYPE_OBJECT | ARG_TYPE_RESOURCE | ARG_TYPE_CALLBACK);



if (!function_exists('syserr'))
{
    function syserr()
    {
        echo '<pre>';
        throw new Exception();
        echo '</pre>';
    }
}


//map a file from the name given
if (!function_exists('map'))
{
    function map($name, &$filename = null, &$pathname = null)
    {
        $heads = array(
            SYSTEM_NAMESPACE => FRAMEWORK_LIBRARY_DIRECTORY,
            APPLICATION_NAMESPACE => APPLICATION_DIRECTORY,
        );
        if (preg_match('/^(((' . implode('|', array_keys($heads)) . ')\.)((\w+\.)*))(\w+|\*)$/', $name, $matches))
        {
            $path = $heads[$matches[3]] . DIRECTORY_SEPARATOR . str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $matches[4]);
            $filename = $matches[6];
            $pathname = substr($matches[1], 0, strlen($matches[1]) - 1);
            if ($filename == '*') return false;
            $file = $path . $filename . '.' . SCRIPT_EXTENSION;
            //echo $file.'<br/>';
            return file_exists($file) ? $file : false;
        }
    }
}

//load a file (require)
if (!function_exists('load'))
{
    function load($name, $once = FALSE)
    {
        if ($file = map($name, $filename, $pathname))
        {
            if ($once)
            {
                return require $file;
            }
            else
            {
                return require_once $file;
            }
        }
        else
        {
            if ($filename == '*')
            {
                $GLOBALS['AUTOLOAD_NAMESPACES'][] = $pathname;
            }
            else
            {
                syserr();
            }
        }
    }
}

//need a file to be loaded (require_once)
if (!function_exists('need'))
{
    function need($name)
    {
        load($name, TRUE);
    }
}

//need all files given to be loaded (require_once)
if (!function_exists('needall'))
{
    function needall($names)
    {
        foreach ($names as $name)
        {
            need($name);
        }
    }
}

if (!function_exists('create'))
{
    function create($name, $arguments = array())
    {
        if (preg_match('/[a-zA-Z_][a-zA-Z_0-9]*/', $name))
        {
            if (!class_exists($name)) spl_autoload_call($name);
            if (!class_exists($name)) syserr();
            $classname = $name;
        }
        elseif ($file = map($name, $classname))
        {
            if (!class_exists($name)) require_once $file;
            if (!class_exists($name)) syserr();
        }
        else syserr();

        if (empty($arguments))
        {
            $instance = new $classname();
        }
        else
        {
            $reflection = new ReflectionClass($classname);
            $instance = $reflection->newInstanceArgs($arguments);
        }
        return $instance;
    }
}




function __autoload($name)
{
    if ($file = map($name))
    {
        require_once $name;
        return;
    }
    $namespaces =& $GLOBALS['AUTOLOAD_NAMESPACES'];
    foreach ($namespaces as $namespace)
    {
        if ($file = map($namespace . NAMESPACE_SEPARATOR . $name))
        {
            require_once $file;
            return;
        }
    }
    syserr();
}



require_once 'system/interfaces.php';

