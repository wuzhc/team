<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_attribute_value_map}}".
 *
 * @property int $id
 * @property int $fdAttributeValueID 属性值ID
 * @property int $fdAttributeID 属性ID
 * @property int $fdGoodsID 商品ID
 */
class GoodsAttributeValueMap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_attribute_value_map}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdAttributeValueID', 'fdAttributeID', 'fdGoodsID'], 'required'],
            [['fdAttributeValueID', 'fdAttributeID', 'fdGoodsID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fdAttributeValueID' => 'Fd Attribute Value ID',
            'fdAttributeID' => 'Fd Attribute ID',
            'fdGoodsID' => 'Fd Goods ID',
        ];
    }
}
