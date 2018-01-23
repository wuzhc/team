<?php

use yii\db\Migration;

/**
 * Class m180123_022533_task_label_map
 */
class m180123_022533_task_label_map extends Migration
{
    public $tableName = '{{%TaskLabelMap}}';

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
            'fdTaskLabelID' => $this->integer(11)->notNull()->comment('标签ID,对应tbTaskLabel.id'),
            'fdTaskID'      => $this->integer(11)->notNull()->comment('任务ID,对应tbTask.id'),
        ], $tableOptions);

        $this->createIndex('taskID', $this->tableName, 'fdTaskID');
        $this->createIndex('taskLabelID', $this->tableName, 'fdTaskLabelID');

        $this->batchInsert($this->tableName, ['fdTaskLabelID', 'fdTaskID'], [
            [1, 1],
            [2, 1],
            [1, 2],
            [1, 3],
            [2, 3],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('taskID', $this->tableName);
        $this->dropIndex('taskLabelID', $this->tableName);
        $this->dropTable($this->tableName);
    }

}
