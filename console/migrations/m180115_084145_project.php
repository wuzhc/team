<?php

use yii\db\Migration;

/**
 * 项目表
 * Class m180115_084145_project
 * @author wuzhc
 * @since 2018-01-15
 */
class m180115_084145_project extends Migration
{
    public $tableName = '{{%Project}}';

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
            'id'            => $this->primaryKey(11)->unsigned(),
            'fdName'        => $this->string(32)->notNull()->comment('项目名称'),
            'fdCreatorID'   => $this->integer(11)->notNull()->comment('创建者,对应tbUser.id'),
            'fdCompanyID'   => $this->integer(11)->notNull()->comment('公司,对应tbCompany.id'),
            'fdDescription' => $this->string(255)->comment('描述'),
            'fdStatus'      => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
            'fdCreate'      => $this->dateTime()->notNull()->comment('创建时间'),
        ], $tableOptions);

        $this->createIndex('creatorID', $this->tableName, 'fdCreatorID');
        $this->createIndex('companyID', $this->tableName, 'fdCompanyID');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('creatorID', $this->tableName);
        $this->dropIndex('companyID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
