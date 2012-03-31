<?php

return
[
    'routes' =>
    [
        new CRegExpRoute('/post-<id:\d+>', 'test', ['<id>']),
        new CRegExpRoute('/<action:\w+>?', '<action:index>'),
        new CRegExpRoute('/<controller:\w+>(/<action:\w+>)?', '<controller>.<action:index>'),
    ],
];
