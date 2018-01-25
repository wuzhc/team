<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Task}}".
 *
 * @property int $id
 * @property string $fdName 任务名称
 * @property int $fdCreatorID 创建者,对应tbUser.id
 * @property int $fdCompanyID 所属公司,对应tbCompany.id
 * @property int $fdProjectID 所属项目,对应tbProject.id
 * @property int $fdTaskCategoryID 任务归类,对应tbTaskCategory.id
 * @property int $fdLevel 任务等级
 * @property int $fdProgress 任务进度,0默认，1开始，2已完成
 * @property int $fdStatus 1可用，2已删除,3正在编辑
 * @property string $fdCreate 创建时间
 * @property string $fdUpdate 更新时间
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdName', 'fdCreatorID', 'fdCompanyID', 'fdProjectID', 'fdCreate', 'fdUpdate'], 'required'],
            [['fdCreatorID', 'fdCompanyID', 'fdProjectID', 'fdProgress', 'fdStatus', 'fdTaskCategoryID', 'fdLevel'], 'integer'],
            [['fdCreate', 'fdUpdate'], 'safe'],
            [['fdName'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fdName' => 'Fd Name',
            'fdCreatorID' => 'Fd Creator ID',
            'fdCompanyID' => 'Fd Company ID',
            'fdProjectID' => 'Fd Project ID',
            'fdLevel' => 'Fd Level',
            'fdTaskCategoryID' => 'Fd Category ID',
            'fdProgress' => 'Fd Progress',
            'fdStatus' => 'Fd Status',
            'fdCreate' => 'Fd Create',
            'fdUpdate' => 'Fd Update',
        ];
    }

    /**
     * 任务清单
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(TaskCategory::className(), ['id' => 'fdTaskCategoryID']);
    }

    /**
     * 任务内容
     * @return \yii\db\ActiveQuery
     */
    public function getTaskContent()
    {
        return $this->hasOne(TaskContent::className(), ['fdTaskID' => 'id']);
    }

    /**
     * 创建者
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'fdCreatorID']);
    }

    /**
     * 所属项目
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'fdProjectID']);
    }
}
