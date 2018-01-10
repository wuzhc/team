<?php

use yii\db\Migration;

class m170821_123857_goods_content extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods_content';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdGoodsID' => $this->integer(11)->comment('商品ID'),
            'fdText' => $this->text()->comment('内容')
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->createIndex('goodsID', $tableName, 'fdGoodsID');
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods_content';
        $this->dropIndex('goodsID', $tableName);
        $this->dropTable($tableName);
    }

}
