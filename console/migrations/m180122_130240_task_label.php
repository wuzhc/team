<?php

use yii\db\Migration;

/**
 * Class m180122_130240_task_label
 */
class m180122_130240_task_label extends Migration
{
    public $tableName = '{{%TaskLabel}}';

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
            'id'          => $this->primaryKey(11)->unsigned(),
            'fdProjectID' => $this->integer(11)->notNull()->comment('所属项目,对应tbProject.id'),
            'fdName'      => $this->string(32)->notNull()->comment('标签名称'),
            'fdColor'     => $this->string(15)->defaultValue('black')->comment('标签颜色'),
            'fdStatus'    => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
        ], $tableOptions);

        $this->createIndex('projectID', $this->tableName, 'fdProjectID');

        $this->batchInsert($this->tableName, ['fdProjectID', 'fdName', 'fdColor', 'fdStatus'],[
            [1, '优化', 'green', 1],
            [1, 'bug', 'red', 1],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('projectID', $this->tableName);
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180122_130240_task_label cannot be reverted.\n";

        return false;
    }
    */
}
