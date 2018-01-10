<?php

use yii\db\Migration;

class m170818_044205_goods_attribute_value_map extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods_attribute_value_map';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdAttributeValueID' => $this->integer(11)->notNull()->comment('属性值ID'),
            'fdAttributeID' => $this->integer(11)->notNull()->comment('属性ID'),
            'fdGoodsID' => $this->integer(11)->notNull()->comment('商品ID')
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdAttributeValueID' => 1,
            'fdAttributeID' => 1,
            'fdGoodsID' => 1
        ]);
        $this->insert($tableName, [
            'fdAttributeValueID' => 4,
            'fdAttributeID' => 2,
            'fdGoodsID' => 1
        ]);
        $this->insert($tableName, [
            'fdAttributeValueID' => 1,
            'fdAttributeID' => 1,
            'fdGoodsID' => 2
        ]);
        $this->insert($tableName, [
            'fdAttributeValueID' => 7,
            'fdAttributeID' => 3,
            'fdGoodsID' => 2
        ]);
        $this->insert($tableName, [
            'fdAttributeValueID' => 7,
            'fdAttributeID' => 3,
            'fdGoodsID' => 3
        ]);
        $this->insert($tableName, [
            'fdAttributeValueID' => 1,
            'fdAttributeID' => 1,
            'fdGoodsID' => 3
        ]);
        $this->insert($tableName, [
            'fdAttributeValueID' => 5,
            'fdAttributeID' => 2,
            'fdGoodsID' => 3
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods_attribute_value_map';
        $this->dropTable($tableName);
    }

}
