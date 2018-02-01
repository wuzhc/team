<?php

use yii\db\Migration;

/**
 * 项目表
 * Class m180119_033653_project
 * @author wuzhc
 * @since 2018-01-15
 */
class m180119_033653_project extends Migration
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
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="项目表"';
        }

        $this->createTable($this->tableName, [
            'id'            => $this->primaryKey(11)->unsigned(),
            'fdName'        => $this->string(32)->notNull()->comment('项目名称'),
            'fdCreatorID'   => $this->integer(11)->notNull()->comment('创建者,对应tbUser.id'),
            'fdCompanyID'   => $this->integer(11)->notNull()->comment('公司,对应tbCompany.id'),
            'fdDescription' => $this->string(255)->comment('描述'),
            'fdStatus'      => $this->smallInteger(1)->defaultValue(0)->comment('1可用，2已删除'),
            'fdCreate'      => $this->dateTime()->notNull()->comment('创建时间'),
            'fdUpdate'      => $this->dateTime()->notNull()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('creatorID', $this->tableName, 'fdCreatorID');
        $this->createIndex('companyID', $this->tableName, 'fdCompanyID');

        $this->batchInsert($this->tableName,[
            'fdName', 'fdCreatorID', 'fdCompanyID', 'fdDescription', 'fdStatus', 'fdCreate', 'fdUpdate'
        ],[
            ['项目一', 1, 1, '项目一', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['项目二', 1, 1, '项目二', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['项目三', 1, 1, '项目三', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['项目四', 1, 1, '项目四', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['项目五', 1, 1, '项目五', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['项目六', 1, 1, '项目六', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['项目七', 1, 1, '项目七', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['项目八', 1, 1, '项目八', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
        ]);
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
