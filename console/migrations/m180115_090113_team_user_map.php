<?php

use yii\db\Migration;

/**
 * 团队成员关联表
 * Class m180115_090113_team_user_map
 * @author wuzhc
 * @since 2018-01-15
 */
class m180115_090113_team_user_map extends Migration
{
    public $tableName = '{{%TeamUserMap}}';

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
            'id'       => $this->primaryKey(11)->unsigned(),
            'fdUserID' => $this->integer(11)->notNull()->comment('成员,对应tbUser.id'),
            'fdTeamID' => $this->integer(11)->notNull()->comment('团队,对应tbTeam.id'),
            'fdStatus' => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
        ], $tableOptions);

        $this->createIndex('userID', $this->tableName, 'fdUserID');
        $this->createIndex('teamID', $this->tableName, 'fdTeamID');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('userID', $this->tableName);
        $this->dropIndex('teamID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
