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

        // 用户组件 add by wuzhc 2018-01-14
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl' => ['user/login']
        ],

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

        // mongo add by wuzhc 2018-01-13
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://'.MONGO_HOST.':'.MONGO_PORT.'/'.MONGO_DB,
//            'options' => [
//                'username' => 'wuzhc',
//                'password' => 'wuzhc'
//            ]
        ],

        // 主题设置 add by wuzhc 2018-01-08
        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                ],
//            ],
        ],

        // adminlte皮肤管理 add by wuzhc 2018-01-08
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-green',
                ],
            ],
        ],

        // 权限管理 add by wuzhc 2018-01-14
        'authManager' => [
            'class' => 'common\components\RbacManager',
            'ruleTable' => '{{%AuthRule}}',
            'itemTable' => '{{%ItemTable}}',
            'itemChildTable' => '{{%AuthItemChild}}',
            'assignmentTable' => '{{%AuthAssignment}}',
        ],
    ],
];
