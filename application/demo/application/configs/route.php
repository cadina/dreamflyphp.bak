<?php

return
[
    'routes' =>
    [
        new CRegExpRoute('/post-<id:\d+>', 'test', ['<id>']),
        new CRegExpRoute('/a-<id:[^ab]+>-b', 'test', ['<id>']),
        new CDefaultRoute(),
    ],
];
