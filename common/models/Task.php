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
 * @property string $fdDescription 描述
 * @property int $fdProgress 任务进度,0默认，1开始，2编辑，3结束
 * @property int $fdStatus 1可用，2已删除
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
            [['fdCreatorID', 'fdCompanyID', 'fdProjectID', 'fdProgress', 'fdStatus', 'fdTaskCategoryID'], 'integer'],
            [['fdCreate', 'fdUpdate'], 'safe'],
            [['fdName'], 'string', 'max' => 32],
            [['fdDescription'], 'string', 'max' => 255],
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
            'fdTaskCategoryID' => 'Fd Category ID',
            'fdDescription' => 'Fd Description',
            'fdProgress' => 'Fd Progress',
            'fdStatus' => 'Fd Status',
            'fdCreate' => 'Fd Create',
            'fdUpdate' => 'Fd Update',
        ];
    }
}
