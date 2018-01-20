<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Project}}".
 *
 * @property int $id
 * @property string $fdName 项目名称
 * @property int $fdCreatorID 创建者,对应tbUser.id
 * @property int $fdCompanyID 公司,对应tbCompany.id
 * @property string $fdDescription 描述
 * @property int $fdStatus 1可用，2已删除
 * @property string $fdCreate 创建时间
 * @property string $fdUpdate 更新时间
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdName', 'fdCreatorID', 'fdCompanyID', 'fdCreate', 'fdUpdate'], 'required', 'message' => '{attribute}不能为空'],
            [['fdCreatorID', 'fdCompanyID', 'fdStatus'], 'integer'],
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
            'id' => '主键ID',
            'fdName' => '名称',
            'fdCreatorID' => '创建者',
            'fdCompanyID' => '公司',
            'fdDescription' => '描述',
            'fdStatus' => '状态',
            'fdCreate' => '创建时间',
            'fdUpdate' => '更新时间',
        ];
    }

    /**
     * 所属创建者
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'fdCreatorID']);
    }

    /**
     * 所属公司
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'fdCompanyID']);
    }
}
