<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%TaskLabel}}".
 *
 * @property int $id
 * @property int $fdProjectID 所属项目,对应tbProject.id
 * @property string $fdName 标签名称
 * @property string $fdColor 标签颜色
 * @property int $fdStatus 1可用，2已删除
 */
class TaskLabel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%TaskLabel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdProjectID', 'fdName'], 'required'],
            [['fdProjectID', 'fdStatus'], 'integer'],
            [['fdName'], 'string', 'max' => 32],
            [['fdColor'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fdProjectID' => 'Fd Project ID',
            'fdName' => 'Fd Name',
            'fdColor' => 'Fd Color',
            'fdStatus' => 'Fd Status',
        ];
    }
}
