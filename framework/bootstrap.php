<?php

/*****************************************************************
 * DreamflyPHP
 * 
 * DreamflyPHP is a lightweight php framework designed for quick
 * and happy web development. We create this framework so that
 * developers can build their web applicatin easily and delightly.
 * Although the developers experience is our major goal, we still
 * want the framework to be stable, expandable, scalable and fast.
 *                                                       by cadina
 *****************************************************************/


/*****************************************************************
 * Definations
 *****************************************************************/

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!defined('NAMESPACE_SEPARATOR')) define('NAMESPACE_SEPARATOR', '.');

if (!defined('NS')) define('NS', NAMESPACE_SEPARATOR);

if (!defined('FRAMEWORK_DIRECTORY')) define('FRAMEWORK_DIRECTORY', __DIR__);

if (!defined('SCRIPT_EXTENSION')) define('SCRIPT_EXTENSION', 'php');


if (!defined('SYSTEM_NAMESPACE')) define('SYSTEM_NAMESPACE', 'system');



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



/*****************************************************************
 * Error Handling
 *****************************************************************/

//raise a system error
if (!function_exists('syserr'))
{
    function syserr()
    {
        echo '<pre>';
        throw new Exception();
        echo '</pre>';
    }
}

/*****************************************************************
 * Package loading
 *****************************************************************/

class _
{
	private static $namespaces = array();

	private static $autoload_namespaces = array();

	private static function find($name, &$filename = null, &$pathname = null)
	{
        if (preg_match('/^(((' . implode('|', array_keys(self::$namespaces)) . ')\.)((\w+\.)*))(\w+|\*)$/', $name, $matches))
        {
            $path = self::$namespaces[$matches[3]] . DIRECTORY_SEPARATOR . str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $matches[4]);
            $filename = $matches[6];
            $pathname = substr($matches[1], 0, strlen($matches[1]) - 1);
            if ($filename == '*') return false;
            $file = $path . $filename . '.' . SCRIPT_EXTENSION;
            //echo $file.'<br/>';
            return file_exists($file) ? $file : false;
        }
	}

	public static function map($namespace, $path)
	{
		if ($path != null)
		{
			self::$namespaces[$namespace] = $path;
		}
		else
		{
			unset(self::$namespaces[$namspace]);
		}
	}
	
    public static function load($name, $once = FALSE)
    {
        if ($file = self::find($name, $filename, $pathname))
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
				self::$autoload_namespaces[] = $pathname;
            }
            else
            {
                syserr();
            }
        }
    }

    public static function create($name, $arguments = array())
    {
        if (preg_match('/[a-zA-Z_][a-zA-Z_0-9]*/', $name))
        {
            if (!class_exists($name)) spl_autoload_call($name);
            if (!class_exists($name)) syserr();
            $classname = $name;
        }
        elseif ($file = self::find($name, $classname))
        {
            if (!class_exists($name)) require_once $file;
            if (!class_exists($name)) syserr();
        }
        else
		{
			syserr();
		}

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

	public static function autoload($name)
	{
		if ($file = self::find($name))
		{
			require_once $name;
			return;
		}
		foreach (self::$autoload_namespaces as $namespace)
		{
			if ($file = find($namespace . NAMESPACE_SEPARATOR . $name))
			{
				require_once $file;
				return;
			}
		}
		syserr();
	}
}


if (!function_exists('map'))
{
	function map($namespace, $path)
	{
		_::map($namespace. $path);
	}
}

if (!function_exists('load'))
{
    function load($name, $once = FALSE)
    {
		_::load($name, $once);
    }
}

if (!function_exists('need'))
{
    function need($name)
    {
		_::load($name, TRUE);
    }
}

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
		_::create($name, $arguments);
    }
}

function __autoload($name)
{
	_::autoload($name);
}


map('', '.' . DS); //current directory
map('^', '..' . DS); //parent direcotory

map(SYSTEM_NAMESPACE, FRAMEWORK_DIRECTORY . DS  . 'system');



/*****************************************************************
 * Preloading
 *****************************************************************/

require_once 'system/interfaces.php';

