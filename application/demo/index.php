<?php

define('DS', DIRECTORY_SEPARATOR);
define('DSPRT', DS.'..');

require __DIR__.DSPRT.DSPRT.DS.'framework'.DS.'bootstrap.php';

$application = new CWebApplication('demo', __DIR__.DS.'application');

$application->run();

