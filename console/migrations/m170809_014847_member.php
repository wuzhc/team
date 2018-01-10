<?php

use yii\db\Migration;

/**
 * 会员表
 * Class m170809_014847_member
 * @since 2017-08-09
 * @author wuzhc2016@163.com
 */
class m170809_014847_member extends Migration
{
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'member';
        $this->createTable($tableName, [
            'id' => $this->primaryKey(11)->unsigned(),
            'fdNickname' => $this->string(32)->notNull()->comment('昵称'),
            'fdName' => $this->string(32)->notNull()->comment('真实姓名'),
            'fdLogin' => $this->string(32)->notNull()->comment('账号'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('账号状态0未完成注册，1正常，2冻结'),
            'fdPhone' => $this->string(11)->comment('手机号码'),
            'fdEmail' => $this->string(64)->comment('邮箱地址'),
            'fdPortrait' => $this->string(255)->comment('头像url'),
            'fdCreate' => $this->dateTime()->notNull()->comment('注册时间'),
            'fdVerify' => $this->dateTime()->null()->comment('账号通过验证时间'),
            'fdLastIP' => $this->string(16)->comment('最后登录IP'),
            'fdLastTime' => $this->dateTime()->comment('最后登录时间'),
            'fdPwdHash' => $this->string(255)->notNull()->comment('密码哈希'),
            'fdPwdResetToken' => $this->string(255)->comment('密码重置token'),
            'fdAuthKey' => $this->string(32)->notNull()->comment('验证key'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->createIndex('login', $tableName, 'fdLogin(10)');

        $this->insert($tableName, [
            'fdNickname' => '啥啥啥',
            'fdName' => '吴生',
            'fdLogin' => 'wuzhc',
            'fdPwdHash' => '$2y$13$D8jfUn7k595cOJbn7muhJeHd1qYqlx4.FSnfqXA2EacD94zw3Ty6.',
            'fdAuthKey' => 'jT4bryRUP7T454ww8IgCJL3Z4HJq_tX7',
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdVerify' => date('Y-m-d H:i:s'),
            'fdEmail' => 'wuzhc2016@163.com',
            'fdStatus' => \common\config\Conf::ENABLE,
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'member';
        $this->dropIndex('login', $tableName);
        $this->dropTable($tableName);
    }
}
