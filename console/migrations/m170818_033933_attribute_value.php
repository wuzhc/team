<?php

use yii\db\Migration;

class m170818_033933_attribute_value extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'attribute_value';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdAttributeID' => $this->integer(11)->notNull()->comment('属性ID，对应zc_attribute.id'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('状态，0未审核，1审核用过，2已删除'),
            'fdTitle' => $this->string(255)->notNull()->comment('标题'),
            'fdSort' => $this->integer(11)->defaultValue(99)->comment('小值排前面'),
            'fdExtra' => $this->string(255)->comment('保留字段'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdAttributeID' => 1,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '黑色',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 1,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '白色',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 1,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '红色',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 2,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '小码',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 2,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '中码',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 2,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '大码',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 3,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '春季',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 3,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '夏季',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 3,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '秋季',
        ]);
        $this->insert($tableName, [
            'fdAttributeID' => 3,
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '冬季',
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'attribute_value';
        $this->dropTable($tableName);
    }

}
