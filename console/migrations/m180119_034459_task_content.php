<?php

use yii\db\Migration;

/**
 * 任务内容表
 * Class m180119_034459_task_content
 * @author wuzhc
 * @since 2018-01-15
 */
class m180119_034459_task_content extends Migration
{
    public $tableName = '{{%TaskContent}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="任务内容表"';
        }

        $this->createTable($this->tableName, [
            'id'        => $this->primaryKey(11)->unsigned(),
            'fdTaskID'  => $this->integer(11)->notNull()->comment('所属任务,对应tbTask.id'),
            'fdContent' => $this->text()->comment('任务内容')
        ], $tableOptions);

        $this->createIndex('taskID', $this->tableName, 'fdTaskID');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('taskID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
