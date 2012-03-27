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


require('system/CLoader.php');

CLoader::registerNamespace('system', __DIR__ . DS . 'system');

CLoader::loadAll([
    'system.*',
    'system.web.*',
    'system.web.routes.*',
]);









