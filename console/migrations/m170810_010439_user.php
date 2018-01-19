<?php

use yii\db\Migration;

/**
 * 用户表
 * Class m170809_010439_user
 * @since 2017-08-09
 * @author wuzhc2016@163.com
 */
class m170810_010439_user extends Migration
{
    public $tableName = '';

    public function init()
    {
        $this->tableName = '{{%User}}';
    }

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(11)->unsigned(),
            'fdName'      => $this->string(32)->notNull()->comment('真实姓名'),
            'fdLogin'     => $this->string(32)->notNull()->comment('账号'),
            'fdPhone'     => $this->string(11)->comment('手机号码'),
            'fdEmail'     => $this->string(64)->comment('邮箱地址'),
            'fdStatus'    => $this->smallInteger(1)->defaultValue(0)->comment('账号状态0未完成注册，1正常，2冻结'),
            'fdRoleID'    => $this->smallInteger(1)->defaultValue(0)->comment('身份，0游客，1超级管理员,2管理员，3成员'),
            'fdCompanyID' => $this->integer(11)->notNull()->comment('当前用户所属公司，对应tbCompany.ID'),
            'fdPassword'  => $this->string(32)->notNull()->comment('密码'),
            'fdSalt'      => $this->string(6)->notNull()->comment('密码干扰项'),
            'fdPortrait'  => $this->string(255)->comment('头像url'),
            'fdPosition'  => $this->string(64)->comment('职位'),
            'fdCreate'    => $this->dateTime()->notNull()->comment('注册时间'),
            'fdVerify'    => $this->dateTime()->null()->comment('账号通过验证时间'),
            'fdLastIP'    => $this->string(16)->comment('最后登录IP'),
            'fdLastTime'  => $this->dateTime()->comment('最后登录时间'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->createIndex('login', $this->tableName, 'fdLogin(10)');

        $this->insert($this->tableName, [
            'fdName'      => '啊牛管理员',
            'fdLogin'     => 'superadmin',
            'fdPassword'  => 'a7aae49067df52022ad3ffc2e3c41096',
            'fdSalt'      => 'gwsegb',
            'fdCompanyID' => 1,
            'fdCreate'    => date('Y-m-d H:i:s'),
            'fdVerify'    => date('Y-m-d H:i:s'),
            'fdEmail'     => 'wuzhc2016@163.com',
            'fdPosition'  => '超级管理员',
            'fdStatus'    => \common\config\Conf::USER_ENABLE,
            'fdRoleID'    => \common\config\Conf::ROLE_SUPER
        ]);
    }

    public function safeDown()
    {
        $this->dropIndex('login', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
