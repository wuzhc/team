<?php

namespace console\controllers;


use Yii;
use yii\console\Controller;

/**
 * 生成表模式文档
 * Class TableSchemaController
 * @package console\controllers
 */
class TableSchemaController extends Controller
{
    /**
     * 数据库表生成
     */
    public function actionCreate()
    {
        global $argv;

        if (!$argv[2] || strcasecmp($argv[2], 'help') === 0) {
            echo "Usage: ./yii table-schema/create [all|tableName] \n";
            exit;
        }

        $db = Yii::$app->db;
        $allTables = $db->getSchema()->getTableNames();

        if ('all' === $argv[2]) {
            $tables = array_diff($allTables, $this->filterTables());
        } else {
            if (!in_array($argv[2], $allTables)) {
                echo sprintf("%s isn't exist \n", $argv[2]);
                exit;
            }
            $tables = (array)$argv[2];
        }

        // 当前数据库没有表
        if (count(array_filter($tables)) == 0) {
            echo "Database has not table \n";
        }

        $root = dirname(dirname(dirname(__FILE__)));
        $fp = fopen($root.'/docs/note/数据库设计及字典说明.md', 'a+');
        if (!$fp) {
            echo "Open file failed \n";
        }

        foreach ($tables as $table) {

            $schema = $db->getTableSchema($table, true);
            if (!$schema->columns) {
                continue;
            }

            fwrite($fp, "#### $schema->name 表 \n");

            // 表头
            $header = "| 字段名 | 类型 | 说明 | \n";
            $header .= "|:--------:|:---------:|:-------:| \n";
            fwrite($fp, $header);

            // 字段
            $row = '';

            foreach ($schema->columns as $col => $obj) {
                $comment = $obj->isPrimaryKey ? '主键' : $obj->comment;
                $row .= "| $obj->name | $obj->dbType | $comment | \n";
            }

            fwrite($fp, $row);
            fwrite($fp, "\r\n\r\n");

            echo "$schema->name successfully \n";
        }

        fclose($fp);
    }

    /**
     * 需要过滤的表
     * @return array
     */
    protected function filterTables()
    {
        $filterTables = [
            'tbmigration',
            'tbAuthAssignment',
            'tbAuthItemChild',
            'tbAuthRule',
            'tbItemTable'
        ];

        return $filterTables;
    }

    /**
     * 所有表
     * @return \string[]
     */
    protected function allTables()
    {
        return Yii::$app->db->getSchema()->getTableNames();
    }

    /**
     * 清空
     */
    public function actionClear()
    {
        $root = dirname(dirname(dirname(__FILE__)));
        $fp = fopen($root.'/docs/note/数据库设计及字典说明.md', 'w');
        if (!$fp) {
            echo "Open file failed \n";
        }

        fwrite($fp, '');
        fclose($fp);

        echo "Clear successfully \n";
    }
}