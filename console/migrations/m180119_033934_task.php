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
            'id'               => $this->primaryKey(11)->unsigned(),
            'fdName'           => $this->string(32)->notNull()->comment('任务名称'),
            'fdCreatorID'      => $this->integer(11)->notNull()->comment('创建者,对应tbUser.id'),
            'fdCompanyID'      => $this->integer(11)->notNull()->comment('所属公司,对应tbCompany.id'),
            'fdProjectID'      => $this->integer(11)->notNull()->comment('所属项目,对应tbProject.id'),
            'fdTaskCategoryID' => $this->integer(11)->defaultValue(0)->comment('任务归类ID,对应tbTaskCategory.id'),
            'fdTaskLabelID'    => $this->smallInteger(1)->defaultValue(0)->comment('标签ID,对应tbTaskLabel.id '),
            'fdProgress'       => $this->smallInteger(1)->defaultValue(0)->comment('任务进度,0默认，1开始，2编辑，3结束'),
            'fdStatus'         => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
            'fdCreate'         => $this->dateTime()->notNull()->comment('创建时间'),
            'fdUpdate'         => $this->dateTime()->notNull()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('creatorID', $this->tableName, 'fdCreatorID');
        $this->createIndex('companyID', $this->tableName, 'fdCompanyID');
        $this->createIndex('projectID', $this->tableName, 'fdProjectID');

        $this->insert($this->tableName, [
            'fdName'           => '我的试卷中三大类型试卷的“我的”二字去掉',
            'fdCreatorID'      => 1,
            'fdCompanyID'      => 1,
            'fdProjectID'      => 1,
            'fdTaskCategoryID' => 1,
            'fdTaskLabelID'    => 1,
            'fdCreate'         => date('Y-m-d H:i:s'),
            'fdUpdate'         => date('Y-m-d H:i:s'),
        ]);

        $this->insert($this->tableName, [
            'fdName'           => '学生端的错题本，修改其来源，来源为某份试卷',
            'fdCreatorID'      => 1,
            'fdCompanyID'      => 1,
            'fdProjectID'      => 1,
            'fdTaskCategoryID' => 1,
            'fdTaskLabelID'    => 2,
            'fdCreate'         => date('Y-m-d H:i:s'),
            'fdUpdate'         => date('Y-m-d H:i:s'),
        ]);
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
