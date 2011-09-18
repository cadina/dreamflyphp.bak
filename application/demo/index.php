<?php

define('DS', DIRECTORY_SEPARATOR);
define('DSPRT', DIRECTORY_SEPARATOR . '..');

define('APPLICATION_DIRECTORY', __DIR__ . DS . 'application');
define('CACHE_DIRECTORY', __DIR__ . DS . 'cache');
define('RESOURCE_DIRECTORY', __DIR__ . DS . 'resource');

require __DIR__. DSPRT . DSPRT . DS . 'framework' . DS . 'bootstrap.php';



need('system.*');
need('system.web.*');
need('application.*');
need('application.models.*');


$application = new CWebApplication(APPLICATION_DIRECTORY);

$application->run();

