<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Company}}".
 *
 * @property int $id
 * @property string $fdName 公司名称
 * @property int $fdCreatorID 创建者,对应tbUser.id
 * @property string $fdDescription 描述
 * @property int $fdStatus 1可用，2已删除
 * @property string $fdCreate 创建时间
 * @property string fdUpdate 更新时间
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdName', 'fdCreatorID', 'fdCreate'], 'required', 'message' => '{attribute}不能为空'],
            [['fdCreatorID', 'fdStatus'], 'integer'],
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
            'id' => '主键',
            'fdName' => '公司名称',
            'fdCreatorID' => '创建者',
            'fdDescription' => '公司简介',
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

}
