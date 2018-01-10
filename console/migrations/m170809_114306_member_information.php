<?php

use yii\db\Migration;

/**
 * 会员其他信息内容
 * Class m170809_114306_member_information
 * @since 2017-08-09
 * @author wuzhc2016@163.com
 */
class m170809_114306_member_information extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'member_information';
        $this->createTable($tableName, [
           'id' => $this->primaryKey()->unsigned(),
            'fdMemberID' => $this->integer(11)->notNull()->comment('会员ID，对应zc_member.id'),
            'fdQQ' => $this->string(10)->comment('QQ'),
            'fdIntro' => $this->string(255)->comment('简介'),
            'fdSex' => $this->smallInteger(1)->defaultValue(0)->comment('性别，0未知，1男，2女'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        // 索引
        $this->createIndex('memberID', $tableName, 'fdMemberID');
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'member_information';
        $this->dropIndex('memberID', $tableName);
        $this->dropTable($tableName);
    }
}
