<?php

use yii\db\Migration;

class m170818_035436_product extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'product';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdGoodsID' => $this->integer(11)->notNull()->comment('商品ID'),
            'fdCreate' => $this->dateTime()->notNull()->comment('创建时间'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('状态，0未审核，1审核用过，2已删除'),
            'fdTitle' => $this->string(255)->notNull()->comment('标题'),
            'fdNo' => $this->string(20)->notNull()->comment('商品编号'),
            'fdSellPrice' => $this->decimal(15, 2)->notNull()->comment('销售价格'),
            'fdMarketPrice' => $this->decimal(15, 2)->notNull()->comment('市场价格'),
            'fdCostPrice' => $this->decimal(15, 2)->notNull()->comment('成本价格'),
            'fdStore' => $this->integer(11)->defaultValue(0)->comment('库存'),
            'fdCover' => $this->string(255)->comment('封面图'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdGoodsID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdTitle' => '男人的风范衬衣',
            'fdCover' => 'http://peixun.cnweike.cn/uploads/course/face/thumbs/39_1415330049.png',
            'fdNo' => 'ZC568791',
            'fdStore' => 23,
            'fdSellPrice' => 940,
            'fdMarketPrice' => 1024,
            'fdCostPrice' => 880,
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'product';
        $this->dropTable($tableName);
    }

}
