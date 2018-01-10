<?php

use yii\db\Migration;

/**
 * 定时通知
 * Class m170810_005020_notice
 */
class m170810_005020_notice extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'notice';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdSendID' => $this->integer(11)->notNull()->comment('发送者ID，对应zc_user.id'),
            'fdCreate' => $this->dateTime()->notNull()->comment('创建时间'),
            'fdBegin' => $this->dateTime()->notNull()->comment('开始时间'),
            'fdEnd' => $this->dateTime()->notNull()->comment('过期时间，过期之后通知会被清除'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('状态，0未执行，1已执行，2已删除'),
            'fdTitle' => $this->string(255)->notNull()->comment('标题'),
            'fdContent' => $this->text()->comment('内容'),
            'fdExtra' => $this->string(255)->comment('保留字段'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdSendID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdBegin' => date('Y-m-d H:i:s'),
            'fdEnd' => date('Y-m-d H:i:s', strtotime('+1 days')),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '啦啦啦，欢迎来到zcshop',
            'fdContent' => '哈哈哈哈哈哈哈哈哈哈哈啊哈哈'
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'notice';
        $this->dropTable($tableName);
    }

}
