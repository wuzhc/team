<?php

use yii\db\Migration;

class m170819_095014_category_brand_map extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'category_rand_map';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdCategoryID' => $this->integer(11)->notNull()->comment('分类ID'),
            'fdBrandID' => $this->integer(11)->notNull()->comment('品牌ID'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdCategoryID' => 1,
            'fdBrandID' => 1,
        ]);
        $this->insert($tableName, [
            'fdCategoryID' => 2,
            'fdBrandID' => 1,
        ]);
        $this->insert($tableName, [
            'fdCategoryID' => 2,
            'fdBrandID' => 2,
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'category_rand_map';
        $this->dropTable($tableName);
    }

}
