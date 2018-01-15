<?php

use yii\db\Migration;

/**
 * 公司表
 * Class m180115_085153_company
 * @author wuzhc
 * @since 2018-01-15
 */
class m180115_085153_company extends Migration
{
    public $tableName = '{{%Company}}';

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
            'fdName'        => $this->string(32)->notNull()->comment('公司名称'),
            'fdCreatorID'   => $this->integer(11)->notNull()->comment('创建者,对应tbUser.id'),
            'fdDescription' => $this->string(255)->comment('描述'),
            'fdStatus'      => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
            'fdCreate'      => $this->dateTime()->notNull()->comment('创建时间'),
        ], $tableOptions);

        $this->createIndex('creatorID', $this->tableName, 'fdCreatorID');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('creatorID', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
