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
            'fdUpdate'      => $this->dateTime()->notNull()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('creatorID', $this->tableName, 'fdCreatorID');

        $this->batchInsert(
            $this->tableName,
            ['fdName','fdCreatorID','fdDescription','fdStatus','fdCreate','fdUpdate'],
            [
                ['阿里巴巴', 1, '阿里巴巴网络技术有限公司（简称：阿里巴巴集团）是以曾担任英语教师的马云为首的18人于1999年在浙江杭州创立，他们相信互联网能够创造公平的竞争环境，让小企业通过创新与科技扩展业务，并在参与国内或全球市场竞争时处于更有利的位置。', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
                ['腾讯', 1, '深圳市腾讯计算机系统有限公司成立于1998年11月[1]  ，由马化腾、张志东、许晨晔、陈一丹、曾李青五位创始人共同创立。[1]  是中国最大的互联网综合服务提供商之一，也是中国服务用户最多的互联网企业之一。[2] ', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
                ['百度', 1, '全球最大的中文搜索引擎、最大的中文网站。1999年底,身在美国硅谷的李彦宏看到了中国互联网及中文搜索引擎服务的巨大发展潜力，抱着技术改变世界的梦想，他毅然辞掉硅谷的高薪工作，携搜索引擎专利技术，于 2000年1月1日在中关村创建了百度公司。', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
                ['广州未名中智教育科技有限公司', 1, '北大未名生物工程集团有限公司（简称：北大未名）是北京大学三大产业集团之一。广州未名中智教育科技有限公司是北大未名集团旗下专业教育科技公司。', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ]
        );
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
