<?php

use yii\db\Migration;

class m170819_062731_brand extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'brand';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdUserID' => $this->integer(11)->notNull()->comment('发布者ID，对应zc_user.id'),
            'fdCreate' => $this->dateTime()->notNull()->comment('创建时间'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('状态，0未审核，1审核用过，2已删除'),
            'fdTitle' => $this->string(255)->notNull()->comment('标题'),
            'fdLink' => $this->string(255)->comment('相关连接'),
            'fdCover' => $this->string(255)->comment('封面图'),
            'fdDesc' => $this->string(255)->comment('品牌简介'),
            'fdSort' => $this->integer(11)->defaultValue(99)->comment('小值排前面'),
            'fdExtra' => $this->string(255)->comment('保留字段'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '海澜之家',
            'fdDesc' => '这是海澜之家的简介',
            'fdCover' => 'http://peixun.cnweike.cn/uploads/course/face/thumbs/39_1415330049.png',
        ]);
        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '鸿星尔克',
            'fdDesc' => '这是鸿星尔克的简介',
            'fdCover' => 'http://peixun.cnweike.cn/uploads/course/face/thumbs/39_1415330049.png',
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'brand';
        $this->dropTable($tableName);
    }

}
