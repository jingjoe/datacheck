<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
        'class' => '\frontend\components\CustomDbSession',
        // 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
        'sessionTable' => 'session_frontend_user', // session table name. Defaults to 'session'.
        ],
    ],
    'modules' => [
      'pdfjs' => [
            'class' => '\yii2assets\pdfjs\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],
];
