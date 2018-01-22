<?php

use yii\db\Migration;

/**
 * Class m180122_112628_task_category
 */
class m180122_112628_task_category extends Migration
{
    public $tableName = '{{%TaskCategory}}';

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
            'fdName'      => $this->string(32)->notNull()->comment('任务名称'),
            'fdStatus'    => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
        ], $tableOptions);

        $this->createIndex('projectID', $this->tableName, 'fdProjectID');

        $this->batchInsert($this->tableName, ['fdProjectID', 'fdName', 'fdStatus'],[
            [1, '课堂1.1版本', 1],
            [1, '课堂1.2版本', 1],
            [1, '课堂2.0版本', 1],
            [1, '课堂3.0版本', 1],
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
        echo "m180122_112628_task_category cannot be reverted.\n";

        return false;
    }
    */
}
