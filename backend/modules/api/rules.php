<?php

return [
    'POST login' => 'site/login',
    'POST signup' => 'site/signup',
    'POST logout' => 'site/logout',

    'GET /' => 'site/index',

    'GET items' => 'item/index',
    'GET items/<id:\d+>' => 'item/view',
    
    'GET genres' => 'genre/index',
    'GET genres/<id:\d+>' => 'genre/view',

    'GET albums' => 'album/index',
    'GET albums/<id:\d+>' => 'album/view',

    'GET artists' => 'artist/index',
    'GET artists/<id:\d+>' => 'artist/view',
];