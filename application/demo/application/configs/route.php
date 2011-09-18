<?php

return array(
    'routes' => array(
        'spec' => array('/post-<id:\d+>', 'home', 'post', array('<id>')),
	    'default' => array('/<controller:\w+>/<action:\w+>', '<controller>', '<action>'),
	),
);
