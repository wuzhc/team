<?php

use yii\db\Migration;

class m170818_035002_category_attribute_map extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'category_attribute_map';
        $this->createTable($tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'fdCategoryID' => $this->integer(11)->notNull()->comment('分类ID'),
            'fdAttributeID' => $this->integer(11)->notNull()->comment('属性ID'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=UTF8');

        $this->insert($tableName, [
            'fdCategoryID' => 1,
            'fdAttributeID' => 1,
        ]);
        $this->insert($tableName, [
            'fdCategoryID' => 2,
            'fdAttributeID' => 1,
        ]);
        $this->insert($tableName, [
            'fdCategoryID' => 2,
            'fdAttributeID' => 2,
        ]);
        $this->insert($tableName, [
            'fdCategoryID' => 2,
            'fdAttributeID' => 3,
        ]);
    }

    public function safeDown()
    {
        $tableName = Yii::$app->db->tablePrefix . 'category_attribute_map';
        $this->dropTable($tableName);
    }

}
