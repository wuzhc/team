<?php

use yii\db\Migration;

class m170818_034344_category extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'category';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdUserID' => $this->integer(11)->notNull()->comment('发布者ID，对应zc_user.id'),
            'fdCreate' => $this->dateTime()->notNull()->comment('创建时间'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('状态，0未审核，1审核用过，2已删除'),
            'fdTitle' => $this->string(255)->notNull()->comment('标题'),
            'fdParentID' => $this->integer(11)->defaultValue(0)->comment('父类ID'),
            'fdSort' => $this->integer(11)->defaultValue(99)->comment('小值排前面'),
            'fdExtra' => $this->string(255)->comment('保留字段'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '男士衬衫',
        ]);
        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '男士鞋子',
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'category';
        $this->dropTable($tableName);
    }

}
