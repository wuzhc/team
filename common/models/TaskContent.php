<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%TaskContent}}".
 *
 * @property int $id
 * @property int $fdTaskID 所属任务,对应tbTask.id
 * @property string $fdContent 任务内容
 */
class TaskContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%TaskContent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdTaskID'], 'required'],
            [['fdTaskID'], 'integer'],
            [['fdContent'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fdTaskID' => 'Fd Task ID',
            'fdContent' => 'Fd Content',
        ];
    }
}
