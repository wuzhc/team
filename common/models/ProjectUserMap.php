<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%ProjectUserMap}}".
 *
 * @property int $id
 * @property int $fdProjectID 所属项目,对应tbProject.id
 * @property int $fdUserID 用户,对应tbUser.id
 * @property int $fdStatus 1可用，2已删除
 */
class ProjectUserMap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ProjectUserMap}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdProjectID', 'fdUserID'], 'required'],
            [['fdProjectID', 'fdUserID', 'fdStatus'], 'integer'],
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
            'fdUserID' => 'Fd User ID',
            'fdStatus' => 'Fd Status',
        ];
    }
}
