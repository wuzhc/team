<?php

use yii\db\Migration;

class m170818_033415_attribute extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'attribute';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdUserID' => $this->integer(11)->notNull()->comment('发布者ID，对应zc_user.id'),
            'fdCreate' => $this->dateTime()->notNull()->comment('创建时间'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('状态，0未审核，1审核用过，2已删除'),
            'fdTitle' => $this->string(255)->notNull()->comment('标题'),
            'fdType' => $this->smallInteger(1)->defaultValue(0)->comment('类型，0单选，1复选，3下拉，4输入框'),
            'fdSort' => $this->integer(11)->defaultValue(99)->comment('小值排前面'),
            'fdExtra' => $this->string(255)->comment('保留字段'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '颜色',
        ]);
        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '尺码',
        ]);
        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '季节',
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'attribute';
        $this->dropTable($tableName);
    }

}
