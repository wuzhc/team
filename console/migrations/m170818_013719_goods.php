<?php

use yii\db\Migration;

class m170818_013719_goods extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdUserID' => $this->integer(11)->notNull()->comment('发布者ID，对应zc_user.id'),
            'fdTitle' => $this->string(255)->notNull()->comment('标题'),
            'fdCategoryID' => $this->integer(11)->notNull()->comment('分类，对应zc_category.id'),
            'fdCreate' => $this->dateTime()->notNull()->comment('创建时间'),
            'fdUp' => $this->dateTime()->comment('上架时间'),
            'fdDown' => $this->dateTime()->comment('下架时间'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('状态，0未审核，1审核用过，2已删除'),
            'fdBrandID' => $this->integer(11)->defaultValue(0)->comment('品牌，对应zc_brand.id'),
            'fdNo' => $this->string(20)->notNull()->comment('商品编号'),
            'fdSellPrice' => $this->decimal(15,2)->defaultValue(999)->comment('销售价格'),
            'fdMarketPrice' => $this->decimal(15,2)->defaultValue(1200)->comment('市场价格'),
            'fdCostPrice' => $this->decimal(15,2)->defaultValue(680)->comment('成本价格'),
            'fdStore' => $this->integer(11)->defaultValue(0)->comment('库存'),
            'fdCover' => $this->string(255)->comment('封面图'),
            'fdDesc' => $this->string(255)->comment('商品简介'),
            'fdWeight' => $this->decimal(15,2)->defaultValue(0)->comment('重量'),
            'fdPoint' => $this->integer(11)->defaultValue(0)->comment('积分'),
            'fdIsHot' => $this->smallInteger(1)->defaultValue(0)->comment('是否热门，0否，1是'),
            'fdIsRecommend' => $this->smallInteger(1)->defaultValue(0)->comment('是否推荐，0否，1是'),
            'fdSort' => $this->integer(11)->defaultValue(99)->comment('小值排前面'),
            'fdExtra' => $this->string(255)->comment('保留字段'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        // 关联表1 计数器(游览数，评论数，购买数)
        // 关联表2 产品表
        // 关联表3 属性值表

        $this->createIndex('userID', $tableName, 'fdUserID');
        $this->createIndex('brandID', $tableName, 'fdBrandID');
        $this->createIndex('categoryID', $tableName, 'fdCategoryID');

        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdUp' => date('Y-m-d H:i:s'),
            'fdStatus' => 1,
            'fdTitle' => '男人的风范衬衣',
            'fdCategoryID' => 1,
            'fdBrandID' => 1,
            'fdDesc' => '啦啦啦，男人的风范衬衣',
            'fdCover' => 'http://peixun.cnweike.cn/uploads/course/face/thumbs/39_1415330049.png',
            'fdNo' => 'ZC568794',
            'fdStore' => 999,
            'fdSellPrice' => 999,
            'fdMarketPrice' => 1024,
            'fdCostPrice' => 880,
        ]);
        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdTitle' => '夏季帆布鞋男士板鞋老北京布鞋透气休闲鞋秋季韩版黑色低帮男鞋子',
            'fdCategoryID' => 2,
            'fdBrandID' => 2,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdUp' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdDesc' => '下单免费赠送运费险和香包，免费试穿',
            'fdCover' => 'https://img.alicdn.com/imgextra/i3/2935656061/TB2IF4Sk4RDOuFjSZFzXXcIipXa_!!2935656061.jpg',
            'fdNo' => 'ZC568794',
            'fdStore' => 999,
            'fdSellPrice' => 999,
            'fdMarketPrice' => 1024,
            'fdCostPrice' => 880,
        ]);
        $this->insert($tableName, [
            'fdUserID' => 1,
            'fdTitle' => '夏季男鞋透气皮鞋男英伦休闲鞋子男韩版潮流百搭板鞋秋季增高',
            'fdCategoryID' => 2,
            'fdBrandID' => 2,
            'fdCreate' => date('Y-m-d H:i:s'),
            'fdUp' => date('Y-m-d H:i:s'),
            'fdStatus' => \common\config\Conf::ENABLE,
            'fdDesc' => '真皮男鞋 低价回馈冲量',
            'fdCover' => 'https://gdp.alicdn.com/imgextra/i2/2086373728/TB2wVn1n84lpuFjy1zjXXcAKpXa_!!2086373728.jpg',
            'fdNo' => 'ZC568794',
            'fdStore' => 296,
            'fdSellPrice' => 138,
            'fdMarketPrice' => 1024,
            'fdCostPrice' => 880,
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'goods';
        $this->dropIndex('categoryID', $tableName);
        $this->dropIndex('brandID', $tableName);
        $this->dropIndex('userID', $tableName);
        $this->dropTable($tableName);
    }

}
