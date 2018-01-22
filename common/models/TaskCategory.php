<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%TaskCategory}}".
 *
 * @property int $id
 * @property int $fdProjectID 所属项目,对应tbProject.id
 * @property string $fdName 任务名称
 * @property int $fdStatus 1可用，2已删除
 */
class TaskCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%TaskCategory}}';
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
            'fdStatus' => 'Fd Status',
        ];
    }
}
