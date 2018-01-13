<?php

/** mongo配置 */
define('MONGO_HOST', '127.0.0.1');
define('MONGO_PORT', '27017');
define('MONGO_DB', 'team');

return [
    'name' => 'Team',
    'language' => 'zh-Hant',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'defaultRoute' => 'default',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [

        // 缓存
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        // 数据库管理
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=team',
            'username' => 'root',
            'password' => 'wuzhc2580',
            'charset' => 'utf8',
            'tablePrefix' => 'tb',
        ],

        // mongo
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://'.MONGO_HOST.':'.MONGO_PORT.'/'.MONGO_DB,
//            'options' => [
//                'username' => 'wuzhc',
//                'password' => 'wuzhc'
//            ]
        ],

        // 主题设置
        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                ],
//            ],
        ],

        // adminlte皮肤管理
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-green',
                ],
            ],
        ],
    ],
];
