<?php

use yii\db\Migration;

/**
 * 任务表
 * Class m180119_033934_task
 * @author wuzhc
 * @since 2018-01-15
 */
class m180119_033934_task extends Migration
{
    public $tableName = '{{%Task}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="任务表"';
        }

        $this->createTable($this->tableName, [
            'id'            => $this->primaryKey(11)->unsigned(),
            'fdName'        => $this->string(32)->notNull()->comment('任务名称'),
            'fdCreatorID'   => $this->integer(11)->notNull()->comment('创建者,对应tbUser.id'),
            'fdCompanyID'   => $this->integer(11)->notNull()->comment('所属公司,对应tbCompany.id'),
            'fdProjectID'   => $this->integer(11)->notNull()->comment('所属项目,对应tbProject.id'),
            'fdDescription' => $this->string(255)->comment('描述'),
            'fdProgress'    => $this->smallInteger(1)->defaultValue(0)->comment('任务进度,0默认，1开始，2编辑，3结束'),
            'fdStatus'      => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
            'fdCreate'      => $this->dateTime()->notNull()->comment('创建时间'),
            'fdUpdate'      => $this->dateTime()->notNull()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('creatorID', $this->tableName, 'fdCreatorID');
        $this->createIndex('companyID', $this->tableName, 'fdCompanyID');
        $this->createIndex('projectID', $this->tableName, 'fdProjectID');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('creatorID', $this->tableName);
        $this->dropIndex('companyID', $this->tableName);
        $this->dropIndex('projectID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
