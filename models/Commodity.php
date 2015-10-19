<?php

namespace app\models;

use app\models\CommodityCategory;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "commodities".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property integer $average_price
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CommodityCategory $category
 */
class Commodity extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'commodities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'average_price', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'category_id' => 'Category ID',
            'average_price' => 'Average Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CommodityCategory::className(), ['id' => 'category_id']);
    }
}
