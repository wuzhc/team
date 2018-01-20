<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Team}}".
 *
 * @property int $id
 * @property string $fdName 项目名称
 * @property int $fdCreatorID 创建者,对应tbUser.id
 * @property int $fdCompanyID 公司,对应tbCompany.id
 * @property string $fdDescription 描述
 * @property int $fdStatus 1可用，2已删除
 * @property int $fdOrder 排序
 * @property string $fdCreate 创建时间
 * @property string $fdUpdate 更新时间
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Team}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdName', 'fdCreatorID', 'fdCompanyID', 'fdCreate'], 'required'],
            [['fdCreatorID', 'fdCompanyID', 'fdStatus'], 'integer'],
            [['fdCreate', 'fdUpdate', 'fdOrder'], 'safe'],
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
            'fdDescription' => 'Fd Description',
            'fdStatus' => 'Fd Status',
            'fdCreate' => 'Fd Create',
            'fdUpdate' => 'Fd Update',
            'fdOrder' => 'Fd Order',
        ];
    }
}
