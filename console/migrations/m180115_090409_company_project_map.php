<?php

use yii\db\Migration;

/**
 * 公司项目关联表
 * Class m180115_090409_company_project_map
 * @author wuzhc
 * @since 2018-01-15
 */
class m180115_090409_company_project_map extends Migration
{
    public $tableName = '{{%CompanyProjectMap}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(11)->unsigned(),
            'fdCompanyID' => $this->integer(11)->notNull()->comment('公司,对应tbCompany.id'),
            'fdProjectID' => $this->integer(11)->notNull()->comment('项目,对应tbProject.id'),
            'fdStatus'    => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
        ], $tableOptions);

        $this->createIndex('companyID', $this->tableName, 'fdCompanyID');
        $this->createIndex('projectID', $this->tableName, 'fdProjectID');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('companyID', $this->tableName);
        $this->dropIndex('projectID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
