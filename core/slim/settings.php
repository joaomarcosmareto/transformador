<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => true, // Allow the web server to send the content-length header
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'app_igreja',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'requestRate' => [
            'timeLimit' => -1,
            'counterLimit' => 20
        ],
        'badLoginRate' => [
            'timeLimit' => -1,
            'counterLimit' => 5
        ],
        'redis' => [
            'pass' => 'LIb62102n',
            'port' => '6379',
            'host' => '127.0.0.1',
        ],
        //TODO: ? getImgPath,...
//        'paths' => [
//            '' => '',
//        ],
        'mongodb' => [
            'dev' => [
                'host' => '127.0.0.1',
                'auth' => false,
                'user' => 'WMAdmin',
                'pass' => '5X6rLZf9fz76D8kM6AQRu',
                'base' => 'igrejateste',
            ],
            'prod' => [
                'host' => '127.0.0.1',
                'auth' => false,
                'user' => 'WMAdmin',
                'pass' => '5X6rLZf9fz76D8kM6AQRu',
                'base' => 'igreja',

            ],
        ]
    ],
];