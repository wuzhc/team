<?php

use common\config\Conf;
use yii\db\Migration;

/**
 * 用户表
 * Class m170809_010439_user
 * @since 2017-08-09
 * @author wuzhc2016@163.com
 */
class m170810_010439_user extends Migration
{
    public $tableName = '{{%User}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="用户表"';
        }

        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(11)->unsigned(),
            'fdName'      => $this->string(32)->notNull()->comment('真实姓名'),
            'fdLogin'     => $this->string(32)->notNull()->comment('账号'),
            'fdPhone'     => $this->string(11)->comment('手机号码'),
            'fdEmail'     => $this->string(64)->comment('邮箱地址'),
            'fdStatus'    => $this->smallInteger(1)->defaultValue(0)->comment('账号状态0未完成注册，1正常，2冻结'),
            'fdRoleID'    => $this->smallInteger(1)->defaultValue(0)->comment('身份，0超级管理员,1管理员，2成员，3游客'),
            'fdCompanyID' => $this->integer(11)->notNull()->comment('所属公司，对应tbCompany.id'),
            'fdTeamID'    => $this->integer(11)->defaultValue(0)->comment('所属团队,对应tbTeam.id'),
            'fdPassword'  => $this->string(32)->notNull()->comment('密码'),
            'fdSalt'      => $this->string(6)->notNull()->comment('密码干扰项'),
            'fdPortrait'  => $this->string(255)->comment('头像url'),
            'fdPosition'  => $this->string(64)->comment('职位'),
            'fdCreate'    => $this->dateTime()->notNull()->comment('注册时间'),
            'fdVerify'    => $this->dateTime()->null()->comment('账号通过验证时间'),
            'fdLastIP'    => $this->string(16)->comment('最后登录IP'),
            'fdLastTime'  => $this->dateTime()->comment('最后登录时间'),
        ], $tableOptions);

        $this->createIndex('login', $this->tableName, 'fdLogin(10)');
        $this->createIndex('companyID', $this->tableName, 'fdCompanyID');
        $this->createIndex('teamID', $this->tableName, 'fdTeamID');

        $this->insert($this->tableName, [
            'fdName'      => '啊牛管理员',
            'fdLogin'     => 'superadmin',
            'fdPassword'  => 'a7aae49067df52022ad3ffc2e3c41096',
            'fdSalt'      => 'gwsegb',
            'fdCompanyID' => 1,
            'fdCreate'    => date('Y-m-d H:i:s'),
            'fdVerify'    => date('Y-m-d H:i:s'),
            'fdEmail'     => 'wuzhc2016@163.com',
            'fdPosition'  => 'Boss',
            'fdStatus'    => \common\config\Conf::USER_ENABLE,
            'fdRoleID'    => \common\config\Conf::ROLE_SUPER
        ]);

        $this->batchInsert($this->tableName, [
            'fdName',
            'fdLogin',
            'fdPassword',
            'fdSalt',
            'fdCompanyID',
            'fdCreate',
            'fdVerify',
            'fdEmail',
            'fdPosition',
            'fdStatus',
            'fdRoleID',
            'fdPortrait',
            'fdTeamID'
        ], [
            [
                '马云',
                'mayun',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'mayun@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][0],
                1
            ],
            [
                '王力宏',
                'wanglihong',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'wanglihong@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][1],
                1
            ],
            [
                '李连杰',
                'lilianjie',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'liliangjie@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][2],
                1
            ],
            [
                '蒋雪儿',
                'jiangxueer',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'jiangxuer@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][3],
                1
            ],
            [
                '马化腾',
                'mahuateng',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'mahuateng@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][4],
                1
            ],
            [
                '刘强东',
                'liuqiangdong',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'liuqiangdong@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][5],
                1
            ],
            [
                '吕永钜',
                'lvyonju',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'lvyonju@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][6],
                1
            ],
            [
                '张飞',
                'zhangfei',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'zhangfei@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][7],
                1
            ],
            [
                '李世民',
                'lishimin',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'lishimin@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][8],
                1
            ],
            [
                '秦烈',
                'qinlie',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'qinlie@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][9],
                1
            ],
            [
                '周传雄',
                'zhouchuanxiong',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'zhouchuanxiong@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][10],
                2
            ],
            [
                '费玉清',
                'feiyuqing',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'feiyuqing@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][11],
                2
            ],
            [
                '杨毅',
                'yangyi',
                'a7aae49067df52022ad3ffc2e3c41096',
                'gwsegb',
                1,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                'yangyi@qq.com',
                '',
                Conf::USER_ENABLE,
                Conf::ROLE_MEMBER,
                Yii::$app->params['defaultPortrait'][12],
                2
            ]
        ]);
    }

    public function safeDown()
    {
        $this->dropIndex('login', $this->tableName);
        $this->dropIndex('companyID', $this->tableName);
        $this->dropIndex('teamID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
