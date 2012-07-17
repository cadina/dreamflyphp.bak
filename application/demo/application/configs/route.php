<?php

return
[
    'routes' =>
    [
        new CPathRoute('/post-<id:\d+>', 'test', '<id>'),
        new CPathRoute('/a-<id:[^ab]+>-b', 'test', '<id>'),
        new CDefaultRoute(),
    ],
];
