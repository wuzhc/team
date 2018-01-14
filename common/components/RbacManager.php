<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;

use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidParamException;
use yii\caching\CacheInterface;
use yii\db\Connection;
use yii\db\Expression;
use yii\db\Query;
use yii\di\Instance;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;

/**
 * DbManager represents an authorization manager that stores authorization information in database.
 *
 * The database connection is specified by [[db]]. The database schema could be initialized by applying migration:
 *
 * ```
 * yii migrate --migrationPath=@yii/rbac/migrations/
 * ```
 *
 * If you don't want to use migration and need SQL instead, files for all databases are in migrations directory.
 *
 * You may change the names of the tables used to store the authorization and rule data by setting [[itemTable]],
 * [[itemChildTable]], [[assignmentTable]] and [[ruleTable]].
 *
 * For more details and usage information on DbManager, see the [guide article on security authorization](guide:security-authorization).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class RbacManager extends \yii\rbac\DbManager
{

    private $_checkAccessAssignments = [];

    /**
     * Performs access check for the specified user.
     * This method is internally called by [[checkAccess()]].
     * @param string|int $user the user ID. This should can be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param string $itemName the name of the operation that need access check
     * @param array $params name-value pairs that would be passed to rules associated
     * with the tasks and roles assigned to the user. A param with name 'user' is added to this array,
     * which holds the value of `$userId`.
     * @param Assignment[] $assignments the assignments to the specified user
     * @return bool whether the operations can be performed by the user.
     */
    protected function checkAccessRecursive($user, $itemName, $params, $assignments)
    {
        if (($item = $this->getItem($itemName)) === null) {
            return false;
        }

        Yii::trace($item instanceof Role ? "Checking role: $itemName" : "Checking permission: $itemName", __METHOD__);

        if (!$this->executeRule($user, $item, $params)) {
            return false;
        }

        if (isset($assignments[$itemName]) || in_array($itemName, $this->defaultRoles)) {
            return true;
        }

        $query = new Query();
        $parents = $query->select(['fdParent'])
            ->from($this->itemChildTable)
            ->where(['fdChild' => $itemName])
            ->column($this->db);
        foreach ($parents as $parent) {
            if ($this->checkAccessRecursive($user, $parent, $params, $assignments)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function getItem($name)
    {
        if (empty($name)) {
            return null;
        }

        if (!empty($this->items[$name])) {
            return $this->items[$name];
        }

        $row = (new Query())->from($this->itemTable)
            ->where(['fdName' => $name])
            ->one($this->db);

        if ($row === false) {
            return null;
        }

        return $this->populateItem($row);
    }

    /**
     * @inheritdoc
     */
    protected function addItem($item)
    {
        $time = time();
        if ($item->createdAt === null) {
            $item->createdAt = $time;
        }
        if ($item->updatedAt === null) {
            $item->updatedAt = $time;
        }
        $this->db->createCommand()
            ->insert($this->itemTable, [
                'fdName' => $item->name,
                'fdType' => $item->type,
                'fdDescription' => $item->description,
                'fdRuleName' => $item->ruleName,
                'fdData' => $item->data === null ? null : serialize($item->data),
                'fdCreate' => $item->createdAt,
                'fdUpdate' => $item->updatedAt,
            ])->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function removeItem($item)
    {
        if (!$this->supportsCascadeUpdate()) {
            $this->db->createCommand()
                ->delete($this->itemChildTable, ['or', '[[fdParent]]=:name', '[[fdChild]]=:name'], [':fdName' => $item->name])
                ->execute();
            $this->db->createCommand()
                ->delete($this->assignmentTable, ['fdItemName' => $item->name])
                ->execute();
        }

        $this->db->createCommand()
            ->delete($this->itemTable, ['fdName' => $item->name])
            ->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function updateItem($name, $item)
    {
        if ($item->name !== $name && !$this->supportsCascadeUpdate()) {
            $this->db->createCommand()
                ->update($this->itemChildTable, ['fdParent' => $item->name], ['fdParent' => $name])
                ->execute();
            $this->db->createCommand()
                ->update($this->itemChildTable, ['fdChild' => $item->name], ['fdChild' => $name])
                ->execute();
            $this->db->createCommand()
                ->update($this->assignmentTable, ['fdItemName' => $item->name], ['fdItemName' => $name])
                ->execute();
        }

        $item->updatedAt = time();

        $this->db->createCommand()
            ->update($this->itemTable, [
                'fdName' => $item->name,
                'fdDescription' => $item->description,
                'fdRuleName' => $item->ruleName,
                'fdData' => $item->data === null ? null : serialize($item->data),
                'fdUpdate' => $item->updatedAt,
            ], [
                'fdName' => $name,
            ])->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function addRule($rule)
    {
        $time = time();
        if ($rule->createdAt === null) {
            $rule->createdAt = $time;
        }
        if ($rule->updatedAt === null) {
            $rule->updatedAt = $time;
        }
        $this->db->createCommand()
            ->insert($this->ruleTable, [
                'fdName' => $rule->name,
                'fdData' => serialize($rule),
                'fdCreate' => $rule->createdAt,
                'fdUpdate' => $rule->updatedAt,
            ])->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function updateRule($name, $rule)
    {
        if ($rule->name !== $name && !$this->supportsCascadeUpdate()) {
            $this->db->createCommand()
                ->update($this->itemTable, ['fdRuleName' => $rule->name], ['fdRuleName' => $name])
                ->execute();
        }

        $rule->updatedAt = time();

        $this->db->createCommand()
            ->update($this->ruleTable, [
                'fdName' => $rule->name,
                'fdData' => serialize($rule),
                'fdUpdate' => $rule->updatedAt,
            ], [
                'name' => $name,
            ])->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function removeRule($rule)
    {
        if (!$this->supportsCascadeUpdate()) {
            $this->db->createCommand()
                ->update($this->itemTable, ['fdRuleName' => null], ['fdRuleName' => $rule->name])
                ->execute();
        }

        $this->db->createCommand()
            ->delete($this->ruleTable, ['fdName' => $rule->name])
            ->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function getItems($type)
    {
        $query = (new Query())
            ->from($this->itemTable)
            ->where(['fdType' => $type]);

        $items = [];
        foreach ($query->all($this->db) as $row) {
            $items[$row['fdName']] = $this->populateItem($row);
        }

        return $items;
    }

    /**
     * Populates an auth item with the data fetched from database.
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected function populateItem($row)
    {
        $class = $row['fdType'] == Item::TYPE_PERMISSION ? Permission::className() : Role::className();

        if (!isset($row['fdData']) || ($data = @unserialize(is_resource($row['fdData']) ? stream_get_contents($row['fdData']) : $row['data'])) === false) {
            $data = null;
        }

        return new $class([
            'name' => $row['fdName'],
            'type' => $row['fdType'],
            'description' => $row['fdDescription'],
            'ruleName' => $row['fdRuleName'],
            'data' => $data,
            'createdAt' => $row['fdCreate'],
            'updatedAt' => $row['fdUpdate'],
        ]);
    }

    /**
     * @inheritdoc
     * The roles returned by this method include the roles assigned via [[$defaultRoles]].
     */
    public function getRolesByUser($userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return [];
        }

        $query = (new Query())->select('b.*')
            ->from(['a' => $this->assignmentTable, 'b' => $this->itemTable])
            ->where('{{a}}.[[fdItemName]]={{b}}.[[fdName]]')
            ->andWhere(['a.fdUserID' => (string) $userId])
            ->andWhere(['b.fdType' => Item::TYPE_ROLE]);

        $roles = $this->getDefaultRoleInstances();
        foreach ($query->all($this->db) as $row) {
            $roles[$row['fdName']] = $this->populateItem($row);
        }

        return $roles;
    }

    /**
     * @inheritdoc
     */
    public function getPermissionsByRole($roleName)
    {
        $childrenList = $this->getChildrenList();
        $result = [];
        $this->getChildrenRecursive($roleName, $childrenList, $result);
        if (empty($result)) {
            return [];
        }
        $query = (new Query())->from($this->itemTable)->where([
            'fdType' => Item::TYPE_PERMISSION,
            'fdName' => array_keys($result),
        ]);
        $permissions = [];
        foreach ($query->all($this->db) as $row) {
            $permissions[$row['fdName']] = $this->populateItem($row);
        }

        return $permissions;
    }

    /**
     * Returns all permissions that are directly assigned to user.
     * @param string|int $userId the user ID (see [[\yii\web\User::id]])
     * @return Permission[] all direct permissions that the user has. The array is indexed by the permission names.
     * @since 2.0.7
     */
    protected function getDirectPermissionsByUser($userId)
    {
        $query = (new Query())->select('b.*')
            ->from(['a' => $this->assignmentTable, 'b' => $this->itemTable])
            ->where('{{a}}.[[fdItemName]]={{b}}.[[fdName]]')
            ->andWhere(['a.fdUserID' => (string) $userId])
            ->andWhere(['b.fdType' => Item::TYPE_PERMISSION]);

        $permissions = [];
        foreach ($query->all($this->db) as $row) {
            $permissions[$row['fdName']] = $this->populateItem($row);
        }

        return $permissions;
    }

    /**
     * Returns all permissions that the user inherits from the roles assigned to him.
     * @param string|int $userId the user ID (see [[\yii\web\User::id]])
     * @return Permission[] all inherited permissions that the user has. The array is indexed by the permission names.
     * @since 2.0.7
     */
    protected function getInheritedPermissionsByUser($userId)
    {
        $query = (new Query())->select('fdItemName')
            ->from($this->assignmentTable)
            ->where(['fdUserID' => (string) $userId]);

        $childrenList = $this->getChildrenList();
        $result = [];
        foreach ($query->column($this->db) as $roleName) {
            $this->getChildrenRecursive($roleName, $childrenList, $result);
        }

        if (empty($result)) {
            return [];
        }

        $query = (new Query())->from($this->itemTable)->where([
            'fdType' => Item::TYPE_PERMISSION,
            'fdName' => array_keys($result),
        ]);
        $permissions = [];
        foreach ($query->all($this->db) as $row) {
            $permissions[$row['fdName']] = $this->populateItem($row);
        }

        return $permissions;
    }

    /**
     * Returns the children for every parent.
     * @return array the children list. Each array key is a parent item name,
     * and the corresponding array value is a list of child item names.
     */
    protected function getChildrenList()
    {
        $query = (new Query())->from($this->itemChildTable);
        $parents = [];
        foreach ($query->all($this->db) as $row) {
            $parents[$row['fdParent']][] = $row['fdChild'];
        }

        return $parents;
    }

    /**
     * @inheritdoc
     */
    public function getRule($name)
    {
        if ($this->rules !== null) {
            return isset($this->rules[$name]) ? $this->rules[$name] : null;
        }

        $row = (new Query())->select(['fdData'])
            ->from($this->ruleTable)
            ->where(['fdName' => $name])
            ->one($this->db);
        if ($row === false) {
            return null;
        }
        $data = $row['fdData'];
        if (is_resource($data)) {
            $data = stream_get_contents($data);
        }

        return unserialize($data);
    }

    /**
     * @inheritdoc
     */
    public function getRules()
    {
        if ($this->rules !== null) {
            return $this->rules;
        }

        $query = (new Query())->from($this->ruleTable);

        $rules = [];
        foreach ($query->all($this->db) as $row) {
            $data = $row['fdData'];
            if (is_resource($data)) {
                $data = stream_get_contents($data);
            }
            $rules[$row['fdName']] = unserialize($data);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getAssignment($roleName, $userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return null;
        }

        $row = (new Query())->from($this->assignmentTable)
            ->where(['fdUserID' => (string) $userId, 'fdItemName' => $roleName])
            ->one($this->db);

        if ($row === false) {
            return null;
        }

        return new Assignment([
            'userId' => $row['fdUserID'],
            'roleName' => $row['fdItemName'],
            'createdAt' => $row['fdCreate'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return [];
        }

        $query = (new Query())
            ->from($this->assignmentTable)
            ->where(['fdUserID' => (string) $userId]);

        $assignments = [];
        foreach ($query->all($this->db) as $row) {
            $assignments[$row['fdItemName']] = new Assignment([
                'userId' => $row['fdUserID'],
                'roleName' => $row['fdItemName'],
                'createdAt' => $row['fdCreate'],
            ]);
        }

        return $assignments;
    }

    /**
     * @inheritdoc
     */
    public function addChild($parent, $child)
    {
        if ($parent->name === $child->name) {
            throw new InvalidParamException("Cannot add '{$parent->name}' as a child of itself.");
        }

        if ($parent instanceof Permission && $child instanceof Role) {
            throw new InvalidParamException('Cannot add a role as a child of a permission.');
        }

        if ($this->detectLoop($parent, $child)) {
            throw new InvalidCallException("Cannot add '{$child->name}' as a child of '{$parent->name}'. A loop has been detected.");
        }

        $this->db->createCommand()
            ->insert($this->itemChildTable, ['fdParent' => $parent->name, 'fdChild' => $child->name])
            ->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function removeChild($parent, $child)
    {
        $result = $this->db->createCommand()
            ->delete($this->itemChildTable, ['fdParent' => $parent->name, 'fdChild' => $child->name])
            ->execute() > 0;

        $this->invalidateCache();

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function removeChildren($parent)
    {
        $result = $this->db->createCommand()
            ->delete($this->itemChildTable, ['fdParent' => $parent->name])
            ->execute() > 0;

        $this->invalidateCache();

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hasChild($parent, $child)
    {
        return (new Query())
            ->from($this->itemChildTable)
            ->where(['fdParent' => $parent->name, 'fdChild' => $child->name])
            ->one($this->db) !== false;
    }

    /**
     * @inheritdoc
     */
    public function getChildren($name)
    {
        $query = (new Query())
            ->select(['fdName', 'fdType', 'fdDescription', 'fdRuleName', 'fdData', 'fdCreate', 'fdUpdate'])
            ->from([$this->itemTable, $this->itemChildTable])
            ->where(['fdParent' => $name, 'fdName' => new Expression('[[fdChild]]')]);

        $children = [];
        foreach ($query->all($this->db) as $row) {
            $children[$row['fdName']] = $this->populateItem($row);
        }

        return $children;
    }

    /**
     * @inheritdoc
     */
    public function assign($role, $userId)
    {
        $assignment = new Assignment([
            'userId' => $userId,
            'roleName' => $role->name,
            'createdAt' => time(),
        ]);

        $this->db->createCommand()
            ->insert($this->assignmentTable, [
                'fdUserID' => $assignment->userId,
                'fdItemName' => $assignment->roleName,
                'fdCreate' => $assignment->createdAt,
            ])->execute();

        unset($this->_checkAccessAssignments[(string) $userId]);
        return $assignment;
    }

    /**
     * @inheritdoc
     */
    public function revoke($role, $userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return false;
        }

        unset($this->_checkAccessAssignments[(string) $userId]);
        return $this->db->createCommand()
            ->delete($this->assignmentTable, ['fdUserID' => (string) $userId, 'fdItemName' => $role->name])
            ->execute() > 0;
    }

    /**
     * @inheritdoc
     */
    public function revokeAll($userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return false;
        }

        unset($this->_checkAccessAssignments[(string) $userId]);
        return $this->db->createCommand()
            ->delete($this->assignmentTable, ['fdUserID' => (string) $userId])
            ->execute() > 0;
    }

    /**
     * Removes all auth items of the specified type.
     * @param int $type the auth item type (either Item::TYPE_PERMISSION or Item::TYPE_ROLE)
     */
    protected function removeAllItems($type)
    {
        if (!$this->supportsCascadeUpdate()) {
            $names = (new Query())
                ->select(['fdName'])
                ->from($this->itemTable)
                ->where(['fdType' => $type])
                ->column($this->db);
            if (empty($names)) {
                return;
            }
            $key = $type == Item::TYPE_PERMISSION ? 'child' : 'parent';
            $this->db->createCommand()
                ->delete($this->itemChildTable, [$key => $names])
                ->execute();
            $this->db->createCommand()
                ->delete($this->assignmentTable, ['fdItemName' => $names])
                ->execute();
        }
        $this->db->createCommand()
            ->delete($this->itemTable, ['fdTYpe' => $type])
            ->execute();

        $this->invalidateCache();
    }

    /**
     * @inheritdoc
     */
    public function removeAllRules()
    {
        if (!$this->supportsCascadeUpdate()) {
            $this->db->createCommand()
                ->update($this->itemTable, ['fdRuleName' => null])
                ->execute();
        }

        $this->db->createCommand()->delete($this->ruleTable)->execute();

        $this->invalidateCache();
    }

    public function loadFromCache()
    {
        if ($this->items !== null || !$this->cache instanceof CacheInterface) {
            return;
        }

        $data = $this->cache->get($this->cacheKey);
        if (is_array($data) && isset($data[0], $data[1], $data[2])) {
            list($this->items, $this->rules, $this->parents) = $data;
            return;
        }

        $query = (new Query())->from($this->itemTable);
        $this->items = [];
        foreach ($query->all($this->db) as $row) {
            $this->items[$row['fdName']] = $this->populateItem($row);
        }

        $query = (new Query())->from($this->ruleTable);
        $this->rules = [];
        foreach ($query->all($this->db) as $row) {
            $data = $row['fdData'];
            if (is_resource($data)) {
                $data = stream_get_contents($data);
            }
            $this->rules[$row['fdName']] = unserialize($data);
        }

        $query = (new Query())->from($this->itemChildTable);
        $this->parents = [];
        foreach ($query->all($this->db) as $row) {
            if (isset($this->items[$row['fdChild']])) {
                $this->parents[$row['fdChild']][] = $row['fdParent'];
            }
        }

        $this->cache->set($this->cacheKey, [$this->items, $this->rules, $this->parents]);
    }

    /**
     * Returns all role assignment information for the specified role.
     * @param string $roleName
     * @return string[] the ids. An empty array will be
     * returned if role is not assigned to any user.
     * @since 2.0.7
     */
    public function getUserIdsByRole($roleName)
    {
        if (empty($roleName)) {
            return [];
        }

        return (new Query())->select('[[fdUserID]]')
            ->from($this->assignmentTable)
            ->where(['fdItemName' => $roleName])->column($this->db);
    }

    /**
     * Check whether $userId is empty.
     * @param mixed $userId
     * @return bool
     */
    private function isEmptyUserId($userId)
    {
        return !isset($userId) || $userId === '';
    }
}
