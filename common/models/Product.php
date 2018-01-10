<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property int $fdGoodsID 商品ID
 * @property string $fdCreate 创建时间
 * @property int $fdStatus 状态，0未审核，1审核用过，2已删除
 * @property string $fdTitle 标题
 * @property string $fdNo 商品编号
 * @property string $fdSellPrice 销售价格
 * @property string $fdMarketPrice 市场价格
 * @property string $fdCostPrice 成本价格
 * @property int $fdStore 库存
 * @property string $fdCover 封面图
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdGoodsID', 'fdCreate', 'fdTitle', 'fdNo', 'fdSellPrice', 'fdMarketPrice', 'fdCostPrice'], 'required'],
            [['fdGoodsID', 'fdStatus', 'fdStore'], 'integer'],
            [['fdCreate'], 'safe'],
            [['fdSellPrice', 'fdMarketPrice', 'fdCostPrice'], 'number'],
            [['fdTitle', 'fdCover'], 'string', 'max' => 255],
            [['fdNo'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fdGoodsID' => 'Fd Goods ID',
            'fdCreate' => 'Fd Create',
            'fdStatus' => 'Fd Status',
            'fdTitle' => 'Fd Title',
            'fdNo' => 'Fd No',
            'fdSellPrice' => 'Fd Sell Price',
            'fdMarketPrice' => 'Fd Market Price',
            'fdCostPrice' => 'Fd Cost Price',
            'fdStore' => 'Fd Store',
            'fdCover' => 'Fd Cover',
        ];
    }
}
