<?php

define('DS', DIRECTORY_SEPARATOR);
define('DSPRT', DIRECTORY_SEPARATOR . '..');

define('APPLICATION_NAMESPACE','application');
define('APPLICATION_DIRECTORY', __DIR__ . DS . 'application');

require __DIR__. DSPRT . DSPRT . DS . 'framework' . DS . 'bootstrap.php';


map(APPLICATION_NAMESPACE, APPLICATION_DIRECTORY);

need('system.*');
need('system.web.*');
need('application.*');


$application = new CWebApplication(APPLICATION_NAMESPACE);

$application->run();

