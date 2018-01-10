<?php

use yii\db\Migration;

class m170819_104512_goods_counter extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods_counter';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdGoodsID' => $this->integer(11)->notNull()->comment('商品数'),
            'fdComments' => $this->integer(11)->defaultValue(0)->comment('评论数'),
            'fdSales' => $this->integer(11)->defaultValue(0)->comment('销售数'),
            'fdViews' => $this->integer(11)->defaultValue(0)->comment('游览数'),
            'fdCollections' => $this->integer(11)->defaultValue(0)->comment('收藏数'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdGoodsID' => 1,
            'fdComments' => 18,
        'fdSales' => 78,
            'fdViews' => 8089,
            'fdCollections' => 61
        ]);
        $this->insert($tableName, [
            'fdGoodsID' => 2,
            'fdComments' => 45,
            'fdSales' => 92,
            'fdViews' => 89546,
            'fdCollections' => 456
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods_counter';
        $this->dropTable($tableName);
    }

}
