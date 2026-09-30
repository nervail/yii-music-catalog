<?php

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.emailVerifyTokenExpire' => 60*60*48,
    'user.passwordMinLength' => 8,
    
    'storageHost' => $_ENV['STORAGE_HOST'],
    'storageBucket' => $_ENV['S3_BUCKET'],
    
    's3Params' => [
        'version' => 'latest',
        'region'  => 'us-east-1',
        'endpoint' => $_ENV['S3_ENDPOINT'],
        'use_path_style_endpoint' => true,
        'credentials' => [
            'key'    => $_ENV['S3_KEY'],      
            'secret' => $_ENV['S3_SECRET'],
        ],
    ],
    'frontendUrl' => $_ENV['FRONTEND_URL'],
];