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
        $this->tableName = Yii::$app->db->tablePrefix . 'User';
    }

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'              => $this->primaryKey(11)->unsigned(),
            'fdName'          => $this->string(32)->notNull()->comment('真实姓名'),
            'fdLogin'         => $this->string(32)->notNull()->comment('账号'),
            'fdStatus'        => $this->smallInteger(1)->defaultValue(0)->comment('账号状态0未完成注册，1正常，2冻结'),
            'fdRoleID'        => $this->smallInteger(1)->defaultValue(0)->comment('身份，0超级管理员'),
            'fdPhone'         => $this->string(11)->comment('手机号码'),
            'fdEmail'         => $this->string(64)->comment('邮箱地址'),
            'fdPortrait'      => $this->string(255)->comment('头像url'),
            'fdCreate'        => $this->dateTime()->notNull()->comment('注册时间'),
            'fdVerify'        => $this->dateTime()->null()->comment('账号通过验证时间'),
            'fdLastIP'        => $this->string(16)->comment('最后登录IP'),
            'fdLastTime'      => $this->dateTime()->comment('最后登录时间'),
            'fdPwdHash'       => $this->string(255)->notNull()->comment('密码哈希'),
            'fdPwdResetToken' => $this->string(255)->comment('密码重置token'),
            'fdAuthKey'       => $this->string(32)->notNull()->comment('验证key'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->createIndex('login', $this->tableName, 'fdLogin(10)');

        $this->insert($this->tableName, [
            'fdName'    => '啊牛管理员',
            'fdLogin'   => 'wuzhencan',
            'fdPwdHash' => '$2y$13$D8jfUn7k595cOJbn7muhJeHd1qYqlx4.FSnfqXA2EacD94zw3Ty6.',
            'fdAuthKey' => 'jT4bryRUP7T454ww8IgCJL3Z4HJq_tX7',
            'fdCreate'  => date('Y-m-d H:i:s'),
            'fdVerify'  => date('Y-m-d H:i:s'),
            'fdEmail'   => 'wuzhc2016@163.com',
            'fdStatus'  => \common\config\Conf::ENABLE,
            'fdRoleID'  => 0
        ]);
    }

    public function safeDown()
    {
        $this->dropIndex('login', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
