<?php

/** mongo配置 */
define('MONGO_ON', true);
define('MONGO_HOST', '127.0.0.1');
define('MONGO_PORT', '27017');
define('MONGO_DB', 'team');

/** 消息推送请求地址 */
define('PUSH_MSG_REQUEST_URL', 'http://localhost:2121');

/** 消息推送秘钥 */
define('PUSH_MSG_SECRET', 'lfweixlwEFW2');

return [
    'name' => 'Team',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'charset' => 'utf-8',
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
            'dsn' => 'mysql:host=127.0.0.1;dbname=team',
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
            'defaultRoles' => ['super', 'admin', 'guest', 'member'],
        ],

        // redis add by wuzhc 2018-01-20
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
    ],

    'controllerMap' => [
        // 数据库迁移 add by wuzhc 2018-01-19
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationTable' => '{{%Migration}}'
        ],
    ],
];
