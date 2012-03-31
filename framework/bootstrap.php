<?php

if (!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

if (!defined('NAMESPACE_SEPARATOR'))
    define('NAMESPACE_SEPARATOR', '.');

if (!defined('NS'))
    define('NS', NAMESPACE_SEPARATOR);

if (!defined('SCRIPT_EXT'))
    define('SCRIPT_EXT', '.php');




spl_autoload_register();

set_exception_handler(function($exception) {
    echo '<pre>';
    throw $exception;
});


require('system/CLoader.php');

function load($name)
{
    if (is_array($name)) {
        return CLoader::loadAll($name);
    }
    return CLoader::load($name);
}

CLoader::enableAutoload();

CLoader::registerNamespace('system', __DIR__ . DS . 'system');

CLoader::loadAll([
    'system.*',
    'system.web.*',
    'system.web.routes.*',
]);









