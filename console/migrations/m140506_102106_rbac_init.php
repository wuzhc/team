<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

/**
 * Initializes RBAC tables.
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m140506_102106_rbac_init extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    /**
     * @return bool
     */
    protected function isMSSQL()
    {
        return $this->db->driverName === 'mssql' || $this->db->driverName === 'sqlsrv' || $this->db->driverName === 'dblib';
    }

    protected function isOracle()
    {
        return $this->db->driverName === 'oci';
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'fdName' => $this->string(64)->notNull(),
            'fdData' => $this->binary(),
            'fdCreate' => $this->integer(),
            'fdUpdate' => $this->integer(),
            'PRIMARY KEY ([[fdName]])',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'fdName' => $this->string(64)->notNull()->comment('名称需唯一'),
            'fdType' => $this->smallInteger()->notNull()->comment('1角色，2权限'),
            'fdDescription' => $this->text(),
            'fdRuleName' => $this->string(64),
            'fdData' => $this->binary(),
            'fdCreate' => $this->integer(),
            'fdUpdate' => $this->integer(),
            'PRIMARY KEY ([[fdName]])',
            'FOREIGN KEY ([[fdRuleName]]) REFERENCES ' . $authManager->ruleTable . ' ([[fdName]])' .
                $this->buildFkClause('ON DELETE SET NULL', 'ON UPDATE CASCADE'),
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'fdType');

        $this->createTable($authManager->itemChildTable, [
            'fdParent' => $this->string(64)->notNull(),
            'fdChild' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[fdParent]], [[fdChild]])',
            'FOREIGN KEY ([[fdParent]]) REFERENCES ' . $authManager->itemTable . ' ([[fdName]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[fdChild]]) REFERENCES ' . $authManager->itemTable . ' ([[fdName]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'fdItemName' => $this->string(64)->notNull(),
            'fdUserID' => $this->string(64)->notNull(),
            'fdCreate' => $this->integer(),
            'PRIMARY KEY ([[fdItemName]], [[fdUserID]])',
            'FOREIGN KEY ([[fdItemName]]) REFERENCES ' . $authManager->itemTable . ' ([[fdName]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ], $tableOptions);

        if ($this->isMSSQL()) {
            $this->execute("CREATE TRIGGER dbo.trigger_auth_item_child
            ON dbo.{$authManager->itemTable}
            INSTEAD OF DELETE, UPDATE
            AS
            DECLARE @old_name VARCHAR (64) = (SELECT name FROM deleted)
            DECLARE @new_name VARCHAR (64) = (SELECT name FROM inserted)
            BEGIN
            IF COLUMNS_UPDATED() > 0
                BEGIN
                    IF @old_name <> @new_name
                    BEGIN
                        ALTER TABLE {$authManager->itemChildTable} NOCHECK CONSTRAINT FK__auth_item__child;
                        UPDATE {$authManager->itemChildTable} SET child = @new_name WHERE child = @old_name;
                    END
                UPDATE {$authManager->itemTable}
                SET fdName = (SELECT name fdName inserted),
                fdType = (SELECT fdType FROM inserted),
                fdDescription = (SELECT fdDescription FROM inserted),
                fdRuleName = (SELECT fdRuleName FROM inserted),
                fdData = (SELECT fdData FROM inserted),
                fdCreate = (SELECT fdCreate FROM inserted),
                fdUpdate = (SELECT fdUpdate FROM inserted)
                WHERE fdName IN (SELECT fdName FROM deleted)
                IF @old_name <> @new_name
                    BEGIN
                        ALTER TABLE {$authManager->itemChildTable} CHECK CONSTRAINT FK__auth_item__child;
                    END
                END
                ELSE
                    BEGIN
                        DELETE FROM dbo.{$authManager->itemChildTable} WHERE fdParent IN (SELECT fdName FROM deleted) OR child IN (SELECT fdName FROM deleted);
                        DELETE FROM dbo.{$authManager->itemTable} WHERE fdName IN (SELECT fdName FROM deleted);
                    END
            END;");
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        if ($this->isMSSQL()) {
            $this->execute('DROP TRIGGER dbo.trigger_auth_item_child;');
        }

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }

    protected function buildFkClause($delete = '', $update = '')
    {
        if ($this->isMSSQL()) {
            return '';
        }

        if ($this->isOracle()) {
            return ' ' . $delete;
        }

        return implode(' ', ['', $delete, $update]);
    }
}
