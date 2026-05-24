<?php

return [
    'POST signup' => 'signup/index',

    'POST login' => 'auth/login',
    'POST logout' => 'auth/logout',

    'POST request-password-reset' => 'reset/request-password-reset',
    'POST reset-password' => 'reset/reset-password',
    'POST check-reset-token' => 'reset/check-reset-token',

    'POST verify-email' => 'verify/verify-email',
    'POST resend-verification-email' => 'verify/resend-verification-email',

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