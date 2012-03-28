<?php

return
[
    'routes' =>
    [
        new CRegExpRoute('/', 'index'),
        new CRegExpRoute('/post-<id:\d+>', 'test', ['<id>']),
        new CRegExpRoute('/<action:\w+>', '<action>'),
        new CRegExpRoute('/<controller:\w+>/<action:\w+>', '<controller>.<action>'),
    ],
];
