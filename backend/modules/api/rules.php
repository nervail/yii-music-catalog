<?php

return [
    'POST login' => 'site/login',
    'POST signup' => 'site/signup',
    'POST logout' => 'site/logout',

    'POST request-password-reset' => 'site/request-password-reset',
    'POST reset-password' => 'site/reset-password',
    'POST check-reset-token' => 'site/check-reset-token',

    'POST verify-email' => 'site/verify-email',
    'POST resend-verification-email' => 'site/resend-verification-email',

    'GET /' => 'site/index',

    'GET items' => 'item/index',
    'GET items/<id:\d+>' => 'item/view',
    
    'GET genres' => 'genre/index',
    'GET genres/<id:\d+>' => 'genre/view',

    'GET albums' => 'album/index',
    'GET albums/<id:\d+>' => 'album/view',

    'GET artists' => 'artist/index',
    'GET artists/<id:\d+>' => 'artist/view',

    'POST subscribe/<id:\d+>' => 'subscription/subscribe',
    'POST unsubscribe/<id:\d+>' => 'subscription/unsubscribe',
];