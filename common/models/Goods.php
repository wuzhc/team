<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property int $id
 * @property int $fdUserID 发布者ID，对应zc_user.id
 * @property string $fdTitle 标题
 * @property int $fdCategoryID 分类，对应zc_category.id
 * @property string $fdCreate 创建时间
 * @property string $fdUp 上架时间
 * @property string $fdDown 下架时间
 * @property int $fdStatus 状态，0未审核，1审核用过，2已删除
 * @property int $fdBrandID 品牌，对应zc_brand.id
 * @property string $fdNo 商品编号
 * @property string $fdSellPrice 销售价格
 * @property string $fdMarketPrice 市场价格
 * @property string $fdCostPrice 成本价格
 * @property int $fdStore 库存
 * @property string $fdCover 封面图
 * @property string $fdDesc 商品简介
 * @property string $fdWeight 重量
 * @property int $fdPoint 积分
 * @property int $fdIsHot 是否热门，0否，1是
 * @property int $fdIsRecommend 是否推荐，0否，1是
 * @property int $fdSort 小值排前面
 * @property string $fdExtra 保留字段
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdUserID', 'fdTitle', 'fdCategoryID', 'fdCreate', 'fdNo'], 'required'],
            [['fdUserID', 'fdCategoryID', 'fdStatus', 'fdBrandID', 'fdStore', 'fdPoint', 'fdIsHot', 'fdIsRecommend', 'fdSort'], 'integer'],
            [['fdCreate', 'fdUp', 'fdDown'], 'safe'],
            [['fdSellPrice', 'fdMarketPrice', 'fdCostPrice', 'fdWeight'], 'number'],
            [['fdTitle', 'fdCover', 'fdDesc', 'fdExtra'], 'string', 'max' => 255],
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
            'fdUserID' => 'Fd User ID',
            'fdTitle' => 'Fd Title',
            'fdCategoryID' => 'Fd Category ID',
            'fdCreate' => 'Fd Create',
            'fdUp' => 'Fd Up',
            'fdDown' => 'Fd Down',
            'fdStatus' => 'Fd Status',
            'fdBrandID' => 'Fd Brand ID',
            'fdNo' => 'Fd No',
            'fdSellPrice' => 'Fd Sell Price',
            'fdMarketPrice' => 'Fd Market Price',
            'fdCostPrice' => 'Fd Cost Price',
            'fdStore' => 'Fd Store',
            'fdCover' => 'Fd Cover',
            'fdDesc' => 'Fd Desc',
            'fdWeight' => 'Fd Weight',
            'fdPoint' => 'Fd Point',
            'fdIsHot' => 'Fd Is Hot',
            'fdIsRecommend' => 'Fd Is Recommend',
            'fdSort' => 'Fd Sort',
            'fdExtra' => 'Fd Extra',
        ];
    }
}
