<?php

use yii\db\Migration;

/**
 * 项目-用户关联表
 * Class m180119_035201_project_user_map
 * @author wuzhc
 * @since 2018-01-15
 */
class m180119_035201_project_user_map extends Migration
{
    public $tableName = '{{%ProjectUserMap}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="项目-用户关联表"';
        }

        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(11)->unsigned(),
            'fdProjectID' => $this->integer(11)->notNull()->comment('所属项目,对应tbProject.id'),
            'fdUserID'    => $this->integer(11)->notNull()->comment('用户,对应tbUser.id'),
            'fdStatus'    => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
        ], $tableOptions);

        $this->createIndex('projectID', $this->tableName, 'fdProjectID');
        $this->createIndex('userID', $this->tableName, 'fdUserID');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('projectID', $this->tableName);
        $this->dropIndex('userID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
