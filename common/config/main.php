<?php
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
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=zcshop',
            'username' => 'root',
            'password' => 'wuzhc2580',
            'charset' => 'utf8',
            'tablePrefix' => 'zc_',
            'on afterOpen' => function ($event) {
                // 打开数据库，立即执行
                // $event->sender->createCommand("set time_zone = '+8:00'")->execute();
            }
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
